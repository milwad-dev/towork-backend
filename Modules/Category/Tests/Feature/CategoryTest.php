<?php

use Modules\Category\Models\Category;
use Modules\Task\Models\Task;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('lists only my categories with task counters', function () {
    $user = login();
    $work = Category::factory()->for($user)->create(['name' => 'Work']);
    Category::factory()->for($user)->create(['name' => 'Errands']);
    Category::factory()->create(['name' => 'Not mine']);
    Task::factory()->for($user)->for($work)->create();
    Task::factory()->for($user)->for($work)->done()->create();

    getJson(api('categories'))
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.0.name', 'Errands')
        ->assertJsonPath('data.1.tasks_count', 2)
        ->assertJsonPath('data.1.open_tasks_count', 1);
});

it('creates, updates and deletes a category', function () {
    login();

    $id = postJson(api('categories'), ['name' => '  Work ', 'color' => 'indigo', 'icon' => 'briefcase'])
        ->assertCreated()
        ->assertJsonPath('data.name', 'Work')
        ->assertJsonPath('data.tasks_count', 0)
        ->json('data.id');

    putJson(api("categories/{$id}"), ['color' => 'rose'])->assertOk()->assertJsonPath('data.color', 'rose');

    deleteJson(api("categories/{$id}"))->assertNoContent();
    expect(Category::count())->toBe(0);
});

it('keeps tasks when their category is deleted', function () {
    $user = login();
    $category = Category::factory()->for($user)->create();
    $task = Task::factory()->for($user)->for($category)->create();

    deleteJson(api("categories/{$category->id}"))->assertNoContent();

    expect($task->fresh()->category_id)->toBeNull();
});

it('validates name uniqueness per user and the colour palette', function () {
    $user = login();
    Category::factory()->for($user)->create(['name' => 'Work']);
    Category::factory()->create(['name' => 'Home']); // someone else's

    postJson(api('categories'), ['name' => 'Work'])->assertJsonValidationErrors('name');
    postJson(api('categories'), ['name' => 'Home'])->assertCreated();
    postJson(api('categories'), ['name' => 'X', 'color' => 'neon'])->assertJsonValidationErrors('color');
});

it("hides other users' categories", function () {
    login();
    $foreign = Category::factory()->create();

    getJson(api("categories/{$foreign->id}"))->assertNotFound();
    putJson(api("categories/{$foreign->id}"), ['name' => 'Hacked'])->assertNotFound();
    deleteJson(api("categories/{$foreign->id}"))->assertNotFound();
});

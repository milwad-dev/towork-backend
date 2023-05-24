<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Task\Enums\TaskPriorityEnum;
use Modules\Task\Enums\TaskStatusEnum;
use Modules\Task\Models\Task;
use Modules\User\Models\User;
use Tests\TestCase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\patchJson;

/*
 * Use refresh database for truncate database for each test.
 */
uses(RefreshDatabase::class);

/**
 * Use Testcase to add some requirements.
 */
uses(TestCase::class);

test('test login user can update tasks successfully', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create();

    $response = actingAs($user)->patchJson(route('tasks.update', ['task' => $task->id]), [
        'title'       => $title = fake()->title,
        'description' => $description = fake()->text,
        'remind_date' => now(),
        'status' => (string) $task->status,
        'priority' => (string) $task->priority,
    ]);
    $response->assertAccepted();
    $response->assertJsonStructure([
        'data',
        'status',
    ]);

    // DB Assertations
    assertDatabaseCount('users', 1);
    assertDatabaseCount('tasks', 1);

    assertDatabaseHas('users', ['email' => $user->email]);
    assertDatabaseHas('tasks', ['title' => $title, 'description' => $description]);
});

test('test guest user can not update tasks successfully', function () {
    $response = patchJson(route('tasks.update' , ['task' => 1]), [
        'title'       => $title = fake()->title,
        'description' => fake()->text,
        'remind_date' => now(),
        'priority'    => (string) TaskPriorityEnum::PRIORITY_FOUR->value,
        'status'      => (string) TaskStatusEnum::STATUS_ACTIVE->value,
    ]);
    $response->assertUnauthorized();
    $response->assertJsonStructure([
        'message',
    ]);
    $response->assertExactJson([
        'message' => 'Unauthenticated.',
    ]);

    // DB Assertations
    assertDatabaseCount('users', 0);
    assertDatabaseCount('tasks', 0);

    assertDatabaseMissing('tasks', ['title' => $title]);
});

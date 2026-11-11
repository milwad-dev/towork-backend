<?php

use Modules\Category\Models\Category;
use Modules\Task\Models\Task;

use function Pest\Laravel\getJson;

it('summarises my tasks', function () {
    $this->travelTo('2030-06-15 12:00:00');
    $user = login();
    $work = Category::factory()->for($user)->create(['name' => 'Work', 'color' => 'indigo']);

    Task::factory()->for($user)->for($work)->dueOn('2030-06-15')->create(['priority' => 4]); // due today
    Task::factory()->for($user)->dueOn('2030-06-01')->create(['priority' => 3]);              // overdue
    Task::factory()->for($user)->inProgress()->dueOn('2030-06-20')->create(['priority' => 2]); // upcoming
    Task::factory()->for($user)->for($work)->done()->create(['completed_at' => '2030-06-15 08:00:00']);
    Task::factory()->for($user)->done()->create(['completed_at' => '2030-06-14 08:00:00']);
    Task::factory()->for($user)->done()->create(['completed_at' => '2030-06-13 08:00:00']);
    Task::factory()->create(); // someone else's

    $response = getJson(api('stats'))->assertOk();

    $response
        ->assertJsonPath('data.totals', [
            'all' => 6, 'todo' => 2, 'in_progress' => 1, 'done' => 3,
            'overdue' => 1, 'due_today' => 1, 'upcoming' => 1,
        ])
        ->assertJsonPath('data.completion_rate', 50)
        ->assertJsonPath('data.by_priority', ['1' => 0, '2' => 1, '3' => 1, '4' => 1])
        ->assertJsonPath('data.by_category.0', ['id' => $work->id, 'name' => 'Work', 'color' => 'indigo', 'total' => 2, 'done' => 1])
        ->assertJsonCount(14, 'data.activity')
        ->assertJsonPath('data.activity.13.date', '2030-06-15')
        ->assertJsonPath('data.activity.13.completed', 1)
        ->assertJsonPath('data.streak', 3);
});

it('works for a brand new account', function () {
    login();

    getJson(api('stats'))
        ->assertOk()
        ->assertJsonPath('data.totals.all', 0)
        ->assertJsonPath('data.completion_rate', 0)
        ->assertJsonPath('data.streak', 0);
});

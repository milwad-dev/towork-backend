<?php

/*
 * Use refresh database for truncate database for each test.
 */

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Task\Enums\TaskPriorityEnum;
use Modules\Task\Enums\TaskStatusEnum;
use Modules\User\Models\User;
use Tests\TestCase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;

uses(RefreshDatabase::class);

/**
 * Use Testcase to add some requirements.
 */
uses(TestCase::class);

test('test login user can store tasks successfully', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->postJson(route('tasks.store'), [
        'title'       => fake()->title,
        'description' => $title = fake()->text,
        'remind_date' => now(),
        'priority'    => (string) TaskPriorityEnum::PRIORITY_FOUR->value,
        'status'      => (string) TaskStatusEnum::STATUS_ACTIVE->value,
    ]);
    $response->assertOk();
    $response->assertJsonStructure([
        'data',
        'status'
    ]);

    // DB Assertations
    assertDatabaseCount('users', 1);
    assertDatabaseCount('tasks', 1);

    assertDatabaseHas('users', $user);
    assertDatabaseHas('tasks', ['title' => $title]);
});

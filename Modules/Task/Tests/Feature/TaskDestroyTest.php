<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Task\Models\Task;
use Modules\User\Models\User;
use Tests\TestCase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;

/*
 * Use refresh database for truncate database for each test.
 */
uses(RefreshDatabase::class);

/**
 * Use Testcase to add some requirements.
 */
uses(TestCase::class);

test('test login user can destroy tasks successfully', function () {
    $user = User::factory()->create();
    $task = Task::factory()->create();

    $response = actingAs($user)->deleteJson(route('tasks.destroy', $task->id));
    $response->assertNoContent();

    // DB Assertations
    assertDatabaseCount('users', 1);
    assertDatabaseCount('tasks', 0);

    assertDatabaseHas('users', ['email' => $user->email]);
    assertDatabaseMissing('tasks', ['title' => $task->title]);
});

test('test guest user can not destroy tasks successfully', function () {
    $task = Task::factory()->create();

    $response = deleteJson(route('tasks.destroy', $task->id));
    $response->assertUnauthorized();
    $response->assertJsonStructure([
        'message',
    ]);
    $response->assertExactJson([
        'message' => 'Unauthenticated.',
    ]);

    // DB Assertations
    assertDatabaseCount('users', 0);
    assertDatabaseCount('tasks', 1);

    assertDatabaseHas('tasks', ['title' => $task->title]);
});

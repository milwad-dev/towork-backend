<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\User;
use Tests\TestCase;
use function Pest\Laravel\actingAs;

/*
 * Use refresh database for truncate database for each test.
 */
uses(RefreshDatabase::class);

/**
 * Use Testcase to add some requirements.
 */
uses(TestCase::class);

test('test login user can see all own tasks', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->getJson(route('tasks.index'));
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            'tasks'
        ]
    ]);
});

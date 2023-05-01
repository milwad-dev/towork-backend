<?php

use Modules\User\Models\User;
use function Pest\Laravel\actingAs;

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

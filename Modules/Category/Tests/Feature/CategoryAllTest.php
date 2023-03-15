<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\User;
use Tests\TestCase;

/*
 * Use refresh database for truncate database for each test.
 */
uses(RefreshDatabase::class);

/**
 * Use Testcase to add some requirements.
 */
uses(TestCase::class);

test('test login user can see all own categories', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson(route('categories.index'));
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            'categories'
        ],
        'status'
    ]);
});


<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\User;
use Tests\TestCase;

use function Pest\Laravel\{actingAs, getJson};

/*
 * Use refresh database for truncate database for each test.
 */
uses(RefreshDatabase::class);

/**
 * Use Testcase to add some requirements.
 */
uses(TestCase::class);

test('admin user can see get all users', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->getJson(route('users.index'));
    $response->assertOk();
    $response->assertJsonStructure([
        'data' => [
            '*' => [
                'name',
                'email',
                'phone',
            ],
        ],
    ]);
});

test('guest user can not see get all users', function () {
    getJson(route('users.index'))->assertUnauthorized();
});

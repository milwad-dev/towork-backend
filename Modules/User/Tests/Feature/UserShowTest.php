<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\User;
use Tests\TestCase;

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas};

/*
 * Use refresh database for truncate database for each test.
 */
uses(RefreshDatabase::class);

/**
 * Use Testcase to add some requirements.
 */
uses(TestCase::class);

test('test admin user can show special user by id', function () {
    $adminUser = User::factory()->create();
    $implcitUser = User::factory()->create();

    $response = actingAs($adminUser)->getJson(route('users.show', $implcitUser->id));
    $response->assertOk();
    $response->assertJsonStructure([
        'data' => [
            'name',
            'email',
            'phone',
        ],
    ]);

    // DB asserts
    assertDatabaseHas('users', ['name' => $adminUser->name]);
    assertDatabaseHas('users', ['name' => $implcitUser->name]);
    assertDatabaseCount('users', 2);
});

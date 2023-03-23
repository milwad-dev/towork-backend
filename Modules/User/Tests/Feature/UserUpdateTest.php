<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\User;
use Tests\TestCase;
use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas, assertDatabaseMissing};

/*
 * Use refresh database for truncate database for each test.
 */
uses(RefreshDatabase::class);

/**
 * Use Testcase to add some requirements.
 */
uses(TestCase::class);

test('test admin user can update user', function () {
    $user = User::factory()->create();

    $updateUser = User::factory()->create();

    $data = User::factory()->make()->toArray();
    $data['password'] = fake()->password.'Aa1@';

    $response = actingAs($user)->patchJson(route('users.update', $updateUser->id), $data);
    $response->assertJson(['status' => 'success']);

    // DB asserts
    assertDatabaseCount('users', 2);
    assertDatabaseMissing('users', $data);
});

test('test admin user can update user when password is not filled', function () {
    $user = User::factory()->create();

    $data = User::factory()->make()->toArray();
    $data['password'] = null;

    $response = actingAs($user)->patchJson(route('users.update', User::factory()->create()->id), $data);
    $response->assertJson(['status' => 'success']);

    $updatedUser = $user->fresh();
    $this->assertEquals($user->password, $updatedUser['password']);

    // DB asserts
    assertDatabaseCount('users', 2);
    assertDatabaseMissing('users', $data);
    assertDatabaseHas('users', ['name' => $data['name']]);
});

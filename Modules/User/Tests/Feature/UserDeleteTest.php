<?php


use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\User;
use Tests\TestCase;
use function Pest\Faker\faker;
use function Pest\Laravel\{postJson}; // Methods
use function Pest\Laravel\{actingAs, assertDatabaseHas, assertDatabaseCount, assertDatabaseMissing};

// DB Asserts

/*
 * Use refresh database for truncate database for each test.
 */
uses(RefreshDatabase::class);

/**
 * Use Testcase to add some requirements.
 */
uses(TestCase::class);

test('test admin user can delete user' , function (){
    $user = User::factory()->create();

    $deleteUser = User::factory()->create(['email' => $email = 'delete@gmail.com']);

    $response = actingAs($user)->deleteJson(route('users.destroy', $deleteUser->id));
    $response->assertJson(['status' => 'success']);

    assertDatabaseMissing('users', ['email' => $email]);
    assertDatabaseCount('users', 1);
});


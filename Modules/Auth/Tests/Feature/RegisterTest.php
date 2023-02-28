<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\User;
use Tests\TestCase;
use function Pest\Faker\fake;
use function Pest\Laravel\{postJson};
use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas, assertDatabaseMissing};

// Methods
// DB Asserts

/*
 * Use refresh database for truncate database for each test.
 */
uses(RefreshDatabase::class);

/**
 * Use Testcase to add some requirements.
 */
uses(TestCase::class);

/*
 * Test user can register with valid data.
 */
test('test user can register', function ()   {
    $name  = fake()->name;
    $email = fake()->email;

    $response = postJson(route('auth.register'), [
        'name'      => $name,
        'email'     => $email,
        'phone'     => 1111111111,
        'password'  => 'Milwad123!'
    ]);
    $response->assertCreated();
    $response->assertJsonStructure([
        'data' => [
            'user',
            'token'
        ],
        'status'
    ]);

    assertDatabaseHas('users', ['name' => $name, 'email' => $email]);
    assertDatabaseCount('users', 1);
});

/*
 * Test exists user can't register.
 */
test('test exists user can not register',  function ()   {
    $name  = 'Milwad';
    $email = 'milwad.dev@gmail.com';

    User::factory()->create(['name' => $name, 'email' => $email]);

    $response = postJson(route('auth.register'), [
        'name'      => $name,
        'email'     => $email,
        'phone'     => 1111111111,
        'password'  => 'Milwad123!'
    ]);
    $response->assertUnprocessable();
    $response->assertJsonStructure([
        'message',
        'errors' => [
            'name'  => [],
            'email' => [],
        ]
    ]);

    assertDatabaseHas('users', ['name' => $name, 'email' => $email]);
    assertDatabaseMissing('users', ['phone' => 1111111111]);
    assertDatabaseCount('users', 1);
});

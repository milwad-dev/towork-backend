<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\User;
use function Pest\Faker\faker;
use function Pest\Laravel\{postJson};
use function Pest\Laravel\{assertDatabaseHas, assertDatabaseCount, assertDatabaseMissing};

uses(RefreshDatabase::class);

test('test user can register', function ()   {
    $name  = faker()->name;
    $email = faker()->email;

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

<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Faker\faker;
use function Pest\Laravel\{postJson};
use function Pest\Laravel\{assertDatabaseHas, assertDatabaseCount};

uses(RefreshDatabase::class);



test('test user can register', static function ()   {
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

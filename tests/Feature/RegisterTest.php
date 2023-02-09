<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Faker\faker;

uses(RefreshDatabase::class);

test('test user can register', function ()  {
    $name  = faker()->name;
    $email = faker()->email;

    $response = $this->postJson(route('auth.register'), [
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
});

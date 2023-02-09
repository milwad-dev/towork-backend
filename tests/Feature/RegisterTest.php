<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Faker\faker;

uses(RefreshDatabase::class);

test('register test', function ()  {
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

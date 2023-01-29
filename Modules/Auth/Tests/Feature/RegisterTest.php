<?php

namespace Modules\Auth\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * Test user can register.
     *
     * @test
     * @return void
     */
    public function user_can_register()
    {
        $name  = $this->faker->name;
        $email = $this->faker->email;

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

        $this->assertDatabaseHas('users', ['name' => $name, 'email' => $email]);
        $this->assertDatabaseCount('users', 1);
    }
}

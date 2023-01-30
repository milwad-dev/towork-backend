<?php

namespace Modules\Auth\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\User\Models\User;
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

    /**
     * Test exists user can not register.
     *
     * @test
     * @return void
     */
    public function exists_user_can_not_register()
    {
        $name  = 'Milwad';
        $email = 'milwad.dev@gmail.com';

        User::factory()->create(['name' => $name, 'email' => $email]);

        $response = $this->postJson(route('auth.register'), [
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

        $this->assertDatabaseHas('users', ['name' => $name, 'email' => $email]);
        $this->assertDatabaseCount('users', 1);
    }
}

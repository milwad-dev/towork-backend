<?php

namespace Modules\Auth\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * Test user can login with email.
     *
     * @test
     * @return void
     */
    public function user_can_login_with_email()
    {
        list($name, $email, $password, $user, $phone) = $this->createUser();

        $response = $this->postJson(route('auth.login'), [
            'email'     => $email,
            'password'  => $password
        ]);
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'token',
                'name',
                'email'
            ],
            'status'
        ]);

        $this->assertDatabaseHas('users', ['name' => $name, 'email' => $email]);
        $this->assertDatabaseCount('users', 1);
        $this->assertEquals($user->email, auth()->user()->email);
        $this->assertAuthenticated();
    }

    /**
     * Test user can not login with email.
     *
     * @test
     * @return void
     */
    public function user_can_not_login_with_email()
    {
        $response = $this->postJson(route('auth.login'), [
            'email'     => 'milwad.dev@gmail.com',
            'password'  => 'Milwad123!'
        ]);
        $response->assertForbidden();
        $response->assertJsonStructure([
            'data' => [
                'message'
            ],
            'status'
        ]);

        $this->assertEquals(false, $this->isAuthenticated());
    }


    /**
     * Test user can login with phone.
     *
     * @test
     * @return void
     */
    public function user_can_login_with_phone()
    {
        [$name, $email, $password, $user, $phone] = $this->createUser();

        $response = $this->postJson(route('auth.login'), [
            'email'     => $phone,
            'password'  => $password
        ]);
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'token',
                'name',
                'email'
            ],
            'status'
        ]);

        $this->assertDatabaseHas('users', ['name' => $name, 'email' => $email, 'phone' => $phone]);
        $this->assertDatabaseCount('users', 1);
        $this->assertEquals($user->email, auth()->user()->email);
        $this->assertEquals($user->phone, auth()->user()->phone);
        $this->assertAuthenticated();
    }

    /**
     * Test user can not login with phone.
     *
     * @test
     * @return void
     */
    public function user_can_not_login_with_phone()
    {
        $response = $this->postJson(route('auth.login'), [
            'email'     => 111111111,
            'password'  => 'Milwad123!'
        ]);
        $response->assertForbidden();
        $response->assertJsonStructure([
            'data' => [
                'message'
            ],
            'status'
        ]);

        $this->assertEquals(false, $this->isAuthenticated());
    }

    /**
     * Create user.
     *
     * @return array
     */
    private function createUser(): array
    {
        $password = 'Milwad123!';

        $user = User::factory()->create([
            'name'      => $name = $this->faker->name,
            'email'     => $email = $this->faker->email,
            'phone'     => $phone = 1111111111,
            'password'  => Hash::make($password)
        ]);

        return array($name, $email, $password, $user, $phone);
    }
}

<?php

namespace Modules\Auth\Tests\Feature\Password;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Models\ResetCodePassword;
use Modules\User\Models\User;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest user can change reset password with valid code.
     *
     * @test
     * @return void
     */
    public function guest_user_can_change_reset_password_with_valid_code()
    {
        User::factory()->create(['email' => $email = 'milwad.dev@gmail.com']);

        // Forgot password
        $this->postJson(route('auth.forgot_password'), ['email' => $email]);

        // Check code
        $code = ResetCodePassword::query()->value('code');
        $this->postJson(route('auth.forgot_password'), ['code' => $code]);

        $response = $this->postJson(route('auth.reset_password'), [
            'email' => $email,
            'code'  => $code
        ]);
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'message'
            ],
            'status'
        ]);

        // DB count asserts
        $this->assertDatabaseCount('reset_code_passwords', 0);
        $this->assertDatabaseCount('users', 1);

        // DB check asserts
        $this->assertDatabaseMissing('reset_code_passwords', ['code' => $code, 'email' => $email]);
        $this->assertDatabaseHas('users', ['email' => $email]);
    }
}

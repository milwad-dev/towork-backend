<?php

namespace Modules\Auth\Tests\Feature\Password;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Models\ResetCodePassword;
use Modules\User\Models\User;
use Tests\TestCase;

class CodeCheckTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest user can send code for forgot password and check is valid.
     *
     * @test
     * @return void
     */
    public function guest_user_can_send_code_for_forgot_password_and_check_is_valid()
    {
        $email = 'milwad.dev@gmail.com';
        User::factory()->create(['email' => $email]);

        $forgotResponse = $this->postJson(route('auth.forgot_password'), ['email' => $email]);
        $forgotResponse->assertOk();
        $forgotResponse->assertJsonStructure([
            'data' => [
                'message'
            ],
            'status'
        ]);

        $response = $this->postJson(route('auth.check_code_password'), [
            'code' => ResetCodePassword::query()->value('code')
        ]);
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'code',
                'message'
            ],
            'status'
        ]);

        $this->assertDatabaseCount('reset_code_passwords', 1);
        $this->assertDatabaseCount('users', 1);
    }
}

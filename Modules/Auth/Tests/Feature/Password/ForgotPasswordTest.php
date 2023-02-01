<?php

namespace Modules\Auth\Tests\Feature\Password;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Modules\Auth\Jobs\SendCodeResetPasswordJob;
use Modules\User\Models\User;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest user can see store reset password code in forgot page.
     *
     * @test
     * @return void
     */
    public function guest_user_can_see_store_reset_password_code_in_forgot_page()
    {
        User::factory()->create(['email' => 'milwad.dev@gmail.com']);

        $response = $this->post(route('auth.forgot_password'), ['email' => 'milwad.dev@gmail.com']);
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                'message'
            ],
            'status'
        ]);

        $this->assertDatabaseCount('reset_code_passwords', 1);
        $this->assertDatabaseCount('users', 1);

//        Queue::fake();
//        Queue::assertPushed(SendCodeResetPasswordJob::class);
    }
}

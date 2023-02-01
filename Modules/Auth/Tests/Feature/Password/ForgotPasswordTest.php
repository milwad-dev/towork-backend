<?php

namespace Modules\Auth\Tests\Feature\Password;

use Illuminate\Foundation\Testing\RefreshDatabase;
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

    }
}

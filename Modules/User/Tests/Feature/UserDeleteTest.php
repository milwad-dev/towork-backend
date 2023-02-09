<?php

namespace Modules\User\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\User;
use Tests\TestCase;

class UserDeleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin user can delete user.
     *
     * @test
     * @return void
     */
    public function admin_user_can_delete_user()
    {
        $user = User::factory()->create();
        $deleteUser = User::factory()->create(['email' => $email = 'delete@gmail.com']);

        // TODO ADD ROLE
        $this->actingAs($user)
            ->deleteJson(route('users.destroy', $deleteUser->id))
            ->assertNoContent();

        // DB asserts
        $this->assertDatabaseMissing('users', ['email' => $email]);
        $this->assertDatabaseCount('users', 1);
    }
}

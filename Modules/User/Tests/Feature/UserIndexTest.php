<?php

namespace Modules\User\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\User;
use Tests\TestCase;

class UserIndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin user can see get all users.
     *
     * @test
     * @return void
     */
    public function admin_user_can_see_get_all_users()
    {
        // TODO Add role
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('users.index'));
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'name',
                    'email',
                    'phone',
                ]
            ]
        ]);
    }

    /**
     * 
     */
}

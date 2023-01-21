<?php

namespace Modules\User\Tests\Feature;

use Modules\User\Models\User;
use Tests\TestCase;

class UserIndexTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
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
}

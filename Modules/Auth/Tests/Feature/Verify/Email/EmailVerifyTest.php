<?php

namespace Modules\Auth\Tests\Feature\Password;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\User;
use Tests\TestCase;
use function Pest\Laravel\{actingAs};

uses(RefreshDatabase::class);
uses(TestCase::class);

test('test user verify account by email', function () {
    $user = User::factory()->create(['email_verified_at' => null]);

    $response = actingAs($user)->postJson(route('auth.email.request'));
    $response->assertOk();
    $response->assertJsonStructure([
        'data' => [
            'message',
        ],
        'status',
    ]);
});

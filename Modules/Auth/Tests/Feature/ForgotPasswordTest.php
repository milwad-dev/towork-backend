<?php

namespace Modules\Auth\Tests\Feature\Password;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Models\ResetCodePassword;
use Modules\User\Models\User;
use Tests\TestCase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\postJson;

uses(TestCase::class);
uses(RefreshDatabase::class);

test('test guest user can see store reset password code in forgot page', function () {
    $user = User::factory()->create();
    $oldResetPasswordCode = ResetCodePassword::factory()->create(['email' => $user->email]);

    $response = postJson(route('auth.forgot_password'), ['email' => $user->email]);
    $response->assertOk();
    $response->assertJsonStructure([
        'data' => [
            'message',
        ],
        'status',
    ]);

    assertDatabaseMissing('reset_code_passwords', $oldResetPasswordCode->toArray());
    assertDatabaseCount('reset_code_passwords', 1);
    assertDatabaseCount('users', 1);

//        Queue::fake();
//        Queue::assertPushed(SendCodeResetPasswordJob::class);
});

test('test login user can not see store reset password code in forgot page', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->postJson(route('auth.forgot_password'), ['email' => $user->email]);
    $response->assertStatus(400);
    $response->assertJsonStructure([
        'message',
    ]);

    assertDatabaseCount('reset_code_passwords', 0);
    assertDatabaseCount('users', 1);

//        Queue::fake();
//        Queue::assertPushed(SendCodeResetPasswordJob::class);
});

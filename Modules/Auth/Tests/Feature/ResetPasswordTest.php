<?php

namespace Modules\Auth\Tests\Feature\Password;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Models\ResetCodePassword;
use Modules\User\Models\User;
use Tests\TestCase;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\postJson;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

uses(TestCase::class);
uses(RefreshDatabase::class);

test('test guest user can reset password with valid code', function () {
    $user = User::factory()->create();
    $resetCodePassword = ResetCodePassword::factory()->create(['email' => $user->email]);

    $response = postJson(route('auth.reset_password'), [
        'code' => $resetCodePassword->code,
        'password' => $password = fake()->password . 'Aa1@'
    ]);

    $response->assertJsonStructure([
        'data' => [
            'message',
        ],
        'status'
    ]);

    assertTrue(Hash::check($password, $user->fresh()->password));
    assertDatabaseCount('users', 1);
    assertDatabaseCount('reset_code_passwords', 0);
});

test('test guest user can  not reset password with invalid code', function () {
    $user = User::factory()->create();
    $resetCodePassword = ResetCodePassword::factory()->create();

    postJson(route('auth.reset_password'), [
        'code' => $resetCodePassword->code,
        'password' => $password = fake()->password . 'Aa1@'
    ]);

    assertFalse(Hash::check($password, $user->fresh()->password));
    assertDatabaseCount('users', 1);
    assertDatabaseCount('reset_code_passwords', 1);
});

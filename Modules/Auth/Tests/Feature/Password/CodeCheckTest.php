<?php

namespace Modules\Auth\Tests\Feature\Password;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Models\ResetCodePassword;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);
uses(TestCase::class);

test('test user reset password code is valid when expiration time not ended', function () {
    $resetCodePassword = ResetCodePassword::factory()->create();

    $response = postJson(route('auth.check_code_password'), [
        'code' => $resetCodePassword->code,
    ]);
    $response->assertOk();
    $response->assertJsonStructure([
        'data' => [
            'code',
            'message',
        ],
        'status',
    ]);

    assertDatabaseCount('reset_code_passwords', 1);
});

test('test user reset password code is not valid when expiration time ended', function () {
    $resetCodePassword = ResetCodePassword::factory()->create(['created_at' => now()->addHours(2)]);

    $response = postJson(route('auth.check_code_password'), [
        'code' => $resetCodePassword->code,
    ]);
    $response->assertStatus(ResponseAlias::HTTP_GONE);
    $response->assertJsonStructure([
        'message',
        'status',
    ]);

    assertDatabaseCount('reset_code_passwords', 0);
});

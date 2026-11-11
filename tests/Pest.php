<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\User;
use Tests\TestCase;

use function Pest\Laravel\actingAs;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('../Modules');

/**
 * Authenticate a (new) user with Sanctum and return it.
 */
function login(?User $user = null): User
{
    $user ??= User::factory()->create();
    actingAs($user, 'sanctum');

    return $user;
}

function api(string $uri = ''): string
{
    return '/api/v1/'.ltrim($uri, '/');
}

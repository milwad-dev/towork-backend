<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Tests\TestCase;
use function Pest\Faker\fake;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\isAuthenticated;
use function Pest\Laravel\postJson;
use function PHPUnit\Framework\assertEquals;

/*
 * Use refresh database for truncate database for each test.
 */
uses(RefreshDatabase::class);

/**
 * Use Testcase to add some requirements.
 */
uses(TestCase::class);

/**
 * Test user can login with email.
 *
 * @test
 *
 * @return void
 */
test('user can login with email', function () {
    [$data, $password] = createUser();

    $response = postJson(route('auth.login'), [
        'email'    => $data['email'],
        'password' => $password,
    ]);

    $response->assertOk();
    $response->assertJsonStructure([
        'data' => [
            'token',
            'name',
            'email',
        ],
        'status',
    ]);

    assertDatabaseHas('users', ['name' => $data['name'], 'email' => $data['email']]);
    assertDatabaseCount('users', 1);
    assertEquals($data['email'], auth('sanctum')->user()->email);
    assertAuthenticated();
});

/**
 * Test user can login with phone when data is wrong .
 *
 * @test
 *
 * @return void
 */
test('user can not login with email with wrong data', function () {
    $response = postJson(route('auth.login'), [
        'email'    => 'milwad.dev@gmail.com',
        'password' => 'Milwad123!',
    ]);
    $response->assertForbidden();
    $response->assertJsonStructure([
        'data' => [
            'message',
        ],
        'status',
    ]);
    assertEquals(false, isAuthenticated());
});

/**
 * Test user can login with phone.
 *
 * @test
 *
 * @return void
 */
test('test user can login with phone', function () {
    [$data, $password] = createUser();

    $response = postJson(route('auth.login'), [
        'email'    => $data['phone'],
        'password' => $password,
    ]);

    $response->assertOk();
    $response->assertJsonStructure([
        'data' => [
            'token',
            'name',
            'email',
        ],
        'status',
    ]);

    assertDatabaseHas('users', [
        'name'  => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone'],
    ]);
    assertDatabaseCount('users', 1);
    assertEquals($data['email'], auth()->user()->email);
    assertEquals($data['phone'], auth()->user()->phone);
    assertAuthenticated();
});

/**
 * Test user can not login with phone when the data is wrong.
 *
 * @test
 *
 * @return void
 */
test('user can not login with phone with wrong data', function () {
    $response = postJson(route('auth.login'), [
        'email'    => 111111111,
        'password' => 'Milwad123!',
    ]);
    $response->assertForbidden();
    $response->assertJsonStructure([
        'data' => [
            'message',
        ],
        'status',
    ]);

    assertEquals(false, isAuthenticated());
});

/**
 * Create new user.
 *
 * @return array
 */
function createUser(): array
{
    $password = 'Milwad123!';

    $user = User::factory()->create([
        'name'     => fake()->name,
        'email'    => fake()->email,
        'phone'    => '09'.fake()->numerify(),
        'password' => Hash::make($password),
    ])->toArray();

    return [$user, $password];
}

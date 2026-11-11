<?php

use Modules\Task\Models\Task;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;

use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\putJson;

it('returns the current user', function () {
    $user = login();

    getJson(api('me'))->assertOk()->assertJsonPath('data.email', $user->email)->assertJsonMissingPath('data.password');
});

it('updates the profile and resets verification when the e-mail changes', function () {
    $user = login();

    putJson(api('me'), ['name' => 'New Name', 'email' => 'new@example.com', 'phone' => '+15551234567'])
        ->assertOk()
        ->assertJsonPath('data.name', 'New Name')
        ->assertJsonPath('data.email_verified_at', null);

    expect($user->fresh()->phone)->toBe('+15551234567');
});

it('keeps verification when the e-mail is unchanged', function () {
    $user = login();

    putJson(api('me'), ['name' => 'Renamed', 'email' => $user->email])->assertOk();

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
});

it('rejects an e-mail used by someone else', function () {
    login();
    $other = User::factory()->create();

    putJson(api('me'), ['name' => 'X', 'email' => $other->email])->assertJsonValidationErrors('email');
});

it('changes the password', function () {
    login();

    putJson(api('me/password'), ['current_password' => 'wrong', 'password' => 'another123', 'password_confirmation' => 'another123'])
        ->assertJsonValidationErrors('current_password');

    putJson(api('me/password'), [
        'current_password'      => UserFactory::PASSWORD,
        'password'              => 'another123',
        'password_confirmation' => 'another123',
    ])->assertNoContent();
});

it('deletes the account and all of its data', function () {
    $user = login();
    Task::factory()->for($user)->count(2)->create();

    deleteJson(api('me'), ['password' => 'wrong'])->assertJsonValidationErrors('password');
    deleteJson(api('me'), ['password' => UserFactory::PASSWORD])->assertNoContent();

    assertDatabaseMissing('users', ['id' => $user->id]);
    expect(Task::count())->toBe(0);
});

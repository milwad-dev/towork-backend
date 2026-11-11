<?php

use Illuminate\Support\Facades\Mail;
use Modules\Auth\Enums\AuthCodePurpose;
use Modules\Auth\Mail\AuthCodeMail;
use Modules\Auth\Models\AuthCode;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

beforeEach(fn () => Mail::fake());

/** Grab the plain code from the last queued AuthCodeMail. */
function lastCode(string $email, AuthCodePurpose $purpose): string
{
    $code = null;
    Mail::assertQueued(AuthCodeMail::class, function (AuthCodeMail $mail) use ($email, $purpose, &$code) {
        if ($mail->hasTo($email) && $mail->purpose === $purpose) {
            $code = $mail->code;
        }

        return true;
    });

    return $code;
}

describe('register', function () {
    it('creates an account, returns a token and e-mails a verification code', function () {
        $response = postJson(api('auth/register'), [
            'name'                  => 'Jane Doe',
            'email'                 => 'Jane@Example.com',
            'password'              => 'secret123',
            'password_confirmation' => 'secret123',
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['data' => ['user' => ['id', 'name', 'email', 'phone', 'email_verified_at'], 'token']])
            ->assertJsonPath('data.user.email', 'jane@example.com')
            ->assertJsonPath('data.user.email_verified_at', null);

        assertDatabaseHas('users', ['email' => 'jane@example.com']);
        Mail::assertQueued(AuthCodeMail::class, fn ($m) => $m->purpose === AuthCodePurpose::VerifyEmail);

        getJson(api('me'), ['Authorization' => 'Bearer '.$response->json('data.token')])->assertOk();
    });

    it('validates input', function () {
        User::factory()->create(['email' => 'taken@example.com']);

        postJson(api('auth/register'), [
            'name'                  => '',
            'email'                 => 'taken@example.com',
            'phone'                 => 'nope',
            'password'              => 'short',
            'password_confirmation' => 'different',
        ])->assertUnprocessable()->assertJsonValidationErrors(['name', 'email', 'phone', 'password']);
    });
});

describe('login', function () {
    it('logs in with e-mail or phone', function (string $field) {
        $user = User::factory()->withPhone()->create();

        postJson(api('auth/login'), ['login' => $user->{$field}, 'password' => UserFactory::PASSWORD])
            ->assertOk()
            ->assertJsonStructure(['data' => ['user', 'token']])
            ->assertJsonPath('data.user.id', $user->id);
    })->with(['email', 'phone']);

    it('rejects wrong credentials with a 422', function () {
        $user = User::factory()->create();

        postJson(api('auth/login'), ['login' => $user->email, 'password' => 'wrong-password'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('login');
    });

    it('logs out by revoking the current token', function () {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        postJson(api('auth/logout'), [], ['Authorization' => "Bearer {$token}"])->assertNoContent();

        expect($user->tokens()->count())->toBe(0);
    });

    it('protects authenticated routes', function () {
        getJson(api('me'))->assertUnauthorized()->assertJson(['message' => 'Unauthenticated.']);
    });
});

describe('password reset', function () {
    it('runs the full forgot → check → reset flow', function () {
        $user = User::factory()->create();
        $user->createToken('old-device');

        postJson(api('auth/forgot-password'), ['email' => $user->email])->assertOk();
        $code = lastCode($user->email, AuthCodePurpose::ResetPassword);

        postJson(api('auth/reset-password/check'), ['email' => $user->email, 'code' => $code])->assertOk();

        postJson(api('auth/reset-password'), [
            'email'                 => $user->email,
            'code'                  => $code,
            'password'              => 'brandnew123',
            'password_confirmation' => 'brandnew123',
        ])->assertOk();

        postJson(api('auth/login'), ['login' => $user->email, 'password' => 'brandnew123'])->assertOk();
        expect($user->tokens()->where('name', 'old-device')->exists())->toBeFalse()
            ->and(AuthCode::count())->toBe(0);
    });

    it('does not reveal whether an e-mail exists', function () {
        postJson(api('auth/forgot-password'), ['email' => 'ghost@example.com'])->assertOk();
        Mail::assertNothingQueued();
    });

    it('rejects wrong and expired codes', function () {
        $user = User::factory()->create();
        postJson(api('auth/forgot-password'), ['email' => $user->email]);
        $code = lastCode($user->email, AuthCodePurpose::ResetPassword);

        $wrong = $code === '123456' ? '654321' : '123456';
        postJson(api('auth/reset-password/check'), ['email' => $user->email, 'code' => $wrong])
            ->assertUnprocessable()->assertJsonValidationErrors('code');

        $this->travel(61)->minutes();
        postJson(api('auth/reset-password/check'), ['email' => $user->email, 'code' => $code])
            ->assertUnprocessable();
    });

    it('locks a code after too many wrong attempts', function () {
        $user = User::factory()->create();
        postJson(api('auth/forgot-password'), ['email' => $user->email]);
        $code = lastCode($user->email, AuthCodePurpose::ResetPassword);
        AuthCode::query()->update(['attempts' => 5]);

        postJson(api('auth/reset-password/check'), ['email' => $user->email, 'code' => $code])->assertUnprocessable();
    });
});

describe('email verification', function () {
    it('verifies the e-mail with the code', function () {
        $user = login(User::factory()->unverified()->create());

        postJson(api('auth/email/verification-notification'))->assertOk();
        $code = lastCode($user->email, AuthCodePurpose::VerifyEmail);

        postJson(api('auth/email/verify'), ['code' => $code])
            ->assertOk()
            ->assertJsonPath('data.id', $user->id);

        expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    });

    it('refuses to resend when already verified', function () {
        login();

        postJson(api('auth/email/verification-notification'))->assertStatus(409);
    });

    it('rejects an invalid code', function () {
        login(User::factory()->unverified()->create());

        postJson(api('auth/email/verify'), ['code' => '000000'])->assertUnprocessable();
    });
});

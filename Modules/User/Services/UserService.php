<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\DB;
use Modules\User\Models\User;

class UserService
{
    public function create(array $data): User
    {
        return User::query()->create([
            'name'     => $data['name'],
            'email'    => strtolower($data['email']),
            'phone'    => $data['phone'] ?? null,
            'password' => $data['password'],
        ]);
    }

    public function updateProfile(User $user, array $data): User
    {
        $user->fill([
            'name'  => $data['name'],
            'email' => strtolower($data['email']),
            'phone' => $data['phone'] ?? null,
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return $user;
    }

    public function updatePassword(User $user, string $password): void
    {
        $user->update(['password' => $password]);

        // Sign out every other device.
        $current = $user->currentAccessToken();
        $user->tokens()->when($current?->id, fn ($q, $id) => $q->whereKeyNot($id))->delete();
    }

    public function delete(User $user): void
    {
        DB::transaction(function () use ($user) {
            $user->tokens()->delete();
            $user->delete(); // tasks, categories and labels cascade
        });
    }

    public function generateToken(User $user, string $name = 'web'): string
    {
        return $user->createToken($name)->plainTextToken;
    }
}

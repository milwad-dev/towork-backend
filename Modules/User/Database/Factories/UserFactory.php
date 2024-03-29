<?php

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\User\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Modules\User\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->safeEmail(),
            'phone'             => '09'.fake()->numerify('#########'),
            'email_verified_at' => now(),
            'password'          => $this->faker->password.'Aa1@', // password
            'remember_token'    => Str::random(10),
        ];
    }
}

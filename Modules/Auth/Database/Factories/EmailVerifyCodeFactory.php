<?php

namespace Modules\Auth\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Models\EmailVerifyCode;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<EmailVerifyCode>
 */
class EmailVerifyCodeFactory extends Factory
{
    protected $model = EmailVerifyCode::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'email'      => fake()->safeEmail(),
            'code'       => fake()->numberBetween(100000, 999999),
            'created_at' => now(),
        ];
    }
}

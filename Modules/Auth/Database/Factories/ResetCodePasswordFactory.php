<?php

namespace Modules\Auth\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Auth\Models\ResetCodePassword;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<ResetCodePassword>
 */
class ResetCodePasswordFactory extends Factory
{
    protected $model = ResetCodePassword::class;

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

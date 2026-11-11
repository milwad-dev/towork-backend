<?php

namespace Modules\Label\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Common\Enums\ColorEnum;
use Modules\Label\Models\Label;
use Modules\User\Models\User;

/**
 * @extends Factory<Label>
 */
class LabelFactory extends Factory
{
    protected $model = Label::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => ucfirst(fake()->unique()->words(fake()->numberBetween(1, 2), true)),
            'color' => fake()->randomElement(ColorEnum::values()),
        ];
    }
}

<?php

namespace Modules\Category\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Category\Models\Category;
use Modules\Common\Enums\ColorEnum;
use Modules\User\Models\User;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => ucfirst(fake()->unique()->words(fake()->numberBetween(1, 2), true)),
            'color' => fake()->randomElement(ColorEnum::values()),
            'icon' => fake()->randomElement(['briefcase', 'home', 'book', 'heart', 'star', null]),
        ];
    }
}

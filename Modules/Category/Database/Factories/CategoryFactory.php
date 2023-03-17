<?php

namespace Modules\Category\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Category\Models\Category;
use Modules\User\Models\User;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return
            [
                'title' => $this->faker->title,
                'user_id' => User::factory()->create()->id,
            ];
    }
}

<?php

namespace Modules\Task\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Task\Enums\TaskPriorityEnum;
use Modules\Task\Enums\TaskStatusEnum;
use Modules\Task\Models\Task;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'       => fake()->title,
            'description' => fake()->text,
            'remind_date' => now(),
            'priority'    => TaskPriorityEnum::PRIORITY_ONE->value,
            'status'      => TaskStatusEnum::STATUS_ACTIVE->value,
        ];
    }
}

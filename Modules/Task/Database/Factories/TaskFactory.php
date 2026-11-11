<?php

namespace Modules\Task\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Task\Enums\TaskPriorityEnum;
use Modules\Task\Enums\TaskStatusEnum;
use Modules\Task\Models\Task;
use Modules\User\Models\User;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'user_id'     => User::factory(),
            'title'       => rtrim(fake()->sentence(fake()->numberBetween(3, 7)), '.'),
            'description' => fake()->optional(0.6)->paragraph(),
            'status'      => TaskStatusEnum::Todo,
            'priority'    => fake()->randomElement(TaskPriorityEnum::cases()),
            'due_date'    => fake()->optional(0.7)->dateTimeBetween('-5 days', '+20 days')?->format('Y-m-d'),
            'position'    => 0,
        ];
    }

    public function done(): static
    {
        return $this->state(['status' => TaskStatusEnum::Done, 'completed_at' => now()]);
    }

    public function inProgress(): static
    {
        return $this->state(['status' => TaskStatusEnum::InProgress]);
    }

    public function dueOn(?string $date): static
    {
        return $this->state(['due_date' => $date]);
    }
}

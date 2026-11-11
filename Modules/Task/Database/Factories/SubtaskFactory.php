<?php

namespace Modules\Task\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Task\Models\Subtask;
use Modules\Task\Models\Task;

/**
 * @extends Factory<Subtask>
 */
class SubtaskFactory extends Factory
{
    protected $model = Subtask::class;

    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'title' => rtrim(fake()->sentence(4), '.'),
            'is_completed' => fake()->boolean(30),
            'position' => 0,
        ];
    }
}

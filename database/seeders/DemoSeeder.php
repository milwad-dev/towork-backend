<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Task\Enums\TaskStatusEnum;
use Modules\User\Models\User;

/**
 * A demo account with realistic data, so the UI looks alive on first run.
 *
 *   e-mail:   demo@towork.test
 *   password: password123
 */
class DemoSeeder extends Seeder
{
    public const EMAIL = 'demo@towork.test';

    public const PASSWORD = 'password123';

    public function run(): void
    {
        if (User::query()->where('email', self::EMAIL)->exists()) {
            $this->command?->info('Demo user already exists, skipping.');

            return;
        }

        DB::transaction(fn () => $this->seedDemo());

        $this->command?->info('Demo user created: '.self::EMAIL.' / '.self::PASSWORD);
    }

    private function seedDemo(): void
    {
        $user = User::query()->create([
            'name' => 'Demo User',
            'email' => self::EMAIL,
            'password' => self::PASSWORD,
        ]);
        $user->markEmailAsVerified();

        $categories = collect([
            ['name' => 'Work', 'color' => 'indigo', 'icon' => 'briefcase'],
            ['name' => 'Personal', 'color' => 'rose', 'icon' => 'heart'],
            ['name' => 'Learning', 'color' => 'amber', 'icon' => 'book-open'],
            ['name' => 'Home', 'color' => 'teal', 'icon' => 'house'],
        ])->mapWithKeys(fn ($c) => [$c['name'] => $user->categories()->create($c)]);

        $labels = collect([
            ['name' => 'quick-win', 'color' => 'green'],
            ['name' => 'waiting', 'color' => 'orange'],
            ['name' => 'meeting', 'color' => 'sky'],
            ['name' => 'focus', 'color' => 'violet'],
        ])->mapWithKeys(fn ($l) => [$l['name'] => $user->labels()->create($l)]);

        $today = now()->startOfDay();
        $d = fn (int $days) => $today->copy()->addDays($days)->toDateString();

        // [title, category, priority, status, due offset (days) or null, time, labels, subtasks, completed days ago]
        $tasks = [
            ['Prepare Q4 roadmap presentation', 'Work', 4, 'in_progress', 0, '15:00', ['focus'], ['Collect metrics', 'Draft slides', 'Review with team'], null],
            ['Reply to design review comments', 'Work', 3, 'todo', 0, null, ['quick-win'], [], null],
            ['1:1 with Sara', 'Work', 2, 'todo', 1, '10:30', ['meeting'], [], null],
            ['Fix flaky login test', 'Work', 3, 'todo', -2, null, ['focus'], ['Reproduce locally', 'Add retry guard'], null],
            ['Update API documentation', 'Work', 2, 'in_progress', 3, null, [], ['Tasks endpoints', 'Auth endpoints'], null],
            ['Wait for legal approval on contract', 'Work', 2, 'todo', 6, null, ['waiting'], [], null],
            ['Ship v1 release', 'Work', 4, 'done', null, null, [], [], 1],
            ['Book dentist appointment', 'Personal', 2, 'todo', 2, null, ['quick-win'], [], null],
            ['Plan weekend hike', 'Personal', 1, 'todo', 4, null, [], ['Check weather', 'Pack snacks'], null],
            ['Call mom', 'Personal', 3, 'done', null, null, [], [], 0],
            ['Buy birthday gift for Alex', 'Personal', 3, 'todo', 9, null, [], [], null],
            ['Finish "Designing Data-Intensive Applications" ch. 5', 'Learning', 2, 'in_progress', 5, null, ['focus'], [], null],
            ['Try Laravel 13 new features', 'Learning', 1, 'todo', null, null, [], [], null],
            ['Watch Vue 3.5 talk', 'Learning', 1, 'done', null, null, [], [], 2],
            ['Water the plants', 'Home', 1, 'todo', 0, null, ['quick-win'], [], null],
            ['Fix the leaking kitchen tap', 'Home', 3, 'todo', -1, null, [], [], null],
            ['Grocery shopping', 'Home', 2, 'done', null, null, [], ['Milk', 'Eggs', 'Coffee'], 3],
            ['Declutter the garage', 'Home', 1, 'todo', 14, null, [], [], null],
            ['Read later: "The Mom Test"', null, 1, 'todo', null, null, [], [], null],
        ];

        $positions = [];
        $i = 0;
        foreach ($tasks as [$title, $cat, $priority, $status, $due, $time, $taskLabels, $subtasks, $doneAgo]) {
            $task = $user->tasks()->create([
                'title' => $title,
                'category_id' => $cat ? $categories[$cat]->id : null,
                'priority' => $priority,
                'status' => $status,
                'due_date' => $due === null ? null : $d($due),
                'due_time' => $time,
                'position' => $positions[$status] = ($positions[$status] ?? -1) + 1,
                'completed_at' => $status === TaskStatusEnum::Done->value ? now()->subDays($doneAgo)->setTime(17, 0) : null,
            ]);

            // Spread creation over the last two weeks so the activity chart looks realistic.
            $createdAgo = $doneAgo !== null ? $doneAgo + 1 + ($i % 3) : $i % 13;
            $task->forceFill(['created_at' => now()->subDays($createdAgo)->setTime(9 + $i % 8, 0)])->saveQuietly();
            $i++;

            $task->labels()->sync(collect($taskLabels)->map(fn ($l) => $labels[$l]->id));

            foreach ($subtasks as $n => $sub) {
                $task->subtasks()->create(['title' => $sub, 'position' => $n, 'is_completed' => $n === 0 || $status === 'done']);
            }
        }

        // A little history for the activity chart.
        $history = ['Inbox zero', 'Weekly review', 'Pay bills', 'Code review', 'Team sync notes', 'Gym session'];
        foreach (range(4, 12) as $daysAgo) {
            if ($daysAgo % 3 === 0) {
                continue;
            }
            $user->tasks()->create([
                'title' => $history[$daysAgo % count($history)],
                'priority' => 2,
                'status' => TaskStatusEnum::Done,
                'completed_at' => now()->subDays($daysAgo)->setTime(18, 0),
                'position' => $positions['done'] = ($positions['done'] ?? -1) + 1,
            ])->forceFill(['created_at' => now()->subDays($daysAgo + 1)->setTime(10, 0)])->saveQuietly();
        }
    }
}

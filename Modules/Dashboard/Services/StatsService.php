<?php

namespace Modules\Dashboard\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Task\Enums\TaskStatusEnum;
use Modules\User\Models\User;

class StatsService
{
    public const ACTIVITY_DAYS = 14;

    public function forUser(User $user): array
    {
        $now = client_now();
        $today = $now->toDateString();
        $tasks = fn () => $user->tasks()->getQuery();

        $byStatus = $tasks()->select('status', DB::raw('count(*) as total'))->groupBy('status')->pluck('total', 'status');
        $all = (int) $byStatus->sum();
        $done = (int) ($byStatus[TaskStatusEnum::Done->value] ?? 0);

        return [
            'totals' => [
                'all' => $all,
                'todo' => (int) ($byStatus[TaskStatusEnum::Todo->value] ?? 0),
                'in_progress' => (int) ($byStatus[TaskStatusEnum::InProgress->value] ?? 0),
                'done' => $done,
                'overdue' => $tasks()->overdue($today)->count(),
                'due_today' => $tasks()->open()->whereDate('due_date', $today)->count(),
                'upcoming' => $tasks()->open()->whereDate('due_date', '>', $today)->count(),
            ],
            'completion_rate' => $all > 0 ? (int) round($done / $all * 100) : 0,
            'by_priority' => $this->byPriority($tasks()),
            'by_category' => $this->byCategory($user),
            'activity' => $this->activity($tasks, $now),
            'streak' => $this->streak($tasks(), $now),
        ];
    }

    private function byPriority($query): array
    {
        $counts = $query->open()->select('priority', DB::raw('count(*) as total'))->groupBy('priority')->pluck('total', 'priority');

        return collect([1, 2, 3, 4])->mapWithKeys(fn ($p) => [(string) $p => (int) ($counts[$p] ?? 0)])->all();
    }

    private function byCategory(User $user): array
    {
        return $user->categories()
            ->withCount(['tasks as total', 'tasks as done' => fn ($q) => $q->where('status', TaskStatusEnum::Done->value)])
            ->orderByDesc('total')
            ->orderBy('name')
            ->get(['id', 'name', 'color'])
            ->map(fn ($c) => ['id' => $c->id, 'name' => $c->name, 'color' => $c->color, 'total' => (int) $c->total, 'done' => (int) $c->done])
            ->all();
    }

    /**
     * Tasks created and completed per (client-local) day over the last N days, oldest first.
     */
    private function activity(callable $tasks, Carbon $now): array
    {
        $tz = $now->getTimezone();
        $start = $now->copy()->startOfDay()->subDays(self::ACTIVITY_DAYS - 1);
        $startUtc = $start->copy()->utc();

        $days = [];
        for ($i = 0; $i < self::ACTIVITY_DAYS; $i++) {
            $days[$start->copy()->addDays($i)->toDateString()] = ['created' => 0, 'completed' => 0];
        }

        // Bucket in PHP so the timezone shift works on every database driver.
        foreach ($tasks()->where('created_at', '>=', $startUtc)->pluck('created_at') as $at) {
            $key = $at->copy()->setTimezone($tz)->toDateString();
            isset($days[$key]) && $days[$key]['created']++;
        }

        foreach ($tasks()->whereNotNull('completed_at')->where('completed_at', '>=', $startUtc)->pluck('completed_at') as $at) {
            $key = Carbon::parse($at)->setTimezone($tz)->toDateString();
            isset($days[$key]) && $days[$key]['completed']++;
        }

        return collect($days)->map(fn ($v, $date) => ['date' => $date, ...$v])->values()->all();
    }

    /**
     * Consecutive days (ending today, or yesterday if nothing is done yet today) with a completed task.
     */
    private function streak($query, Carbon $now): int
    {
        $tz = $now->getTimezone();

        $dates = $query->whereNotNull('completed_at')
            ->where('completed_at', '>=', $now->copy()->subDays(366)->utc())
            ->pluck('completed_at')
            ->map(fn ($at) => Carbon::parse($at)->setTimezone($tz)->toDateString())
            ->unique()
            ->flip();

        $day = $now->copy()->startOfDay();
        if (! isset($dates[$day->toDateString()])) {
            $day->subDay();
        }

        $streak = 0;
        while (isset($dates[$day->toDateString()])) {
            $streak++;
            $day->subDay();
        }

        return $streak;
    }
}

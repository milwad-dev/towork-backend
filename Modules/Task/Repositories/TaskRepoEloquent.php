<?php

namespace Modules\Task\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Modules\Task\Models\Task;
use Modules\User\Models\User;

class TaskRepoEloquent
{
    /** Relations and counters every task response needs. */
    public function withDetails(Builder $query): Builder
    {
        return $query
            ->with(['category:id,name,color,icon', 'labels:id,name,color', 'subtasks'])
            ->withCount(['subtasks', 'subtasks as completed_subtasks_count' => fn (Builder $q) => $q->where('is_completed', true)]);
    }

    public function loadDetails(Task $task): Task
    {
        return $task->load(['category:id,name,color,icon', 'labels:id,name,color', 'subtasks'])
            ->loadCount(['subtasks', 'subtasks as completed_subtasks_count' => fn (Builder $q) => $q->where('is_completed', true)]);
    }

    /**
     * Filter, sort and paginate a user's tasks (see docs/api-v1.md for the parameters).
     */
    public function paginate(User $user, array $filters): LengthAwarePaginator
    {
        $query = $this->withDetails($user->tasks()->getQuery());
        $today = client_now()->toDateString();

        $this->applyFilters($query, $filters, $today);
        $this->applySort($query, $filters['sort'] ?? 'position', $filters['direction'] ?? null);

        return $query->paginate((int) ($filters['per_page'] ?? 50))->withQueryString();
    }

    private function applyFilters(Builder $query, array $f, string $today): void
    {
        if (filled($f['search'] ?? null)) {
            // "!" as escape character behaves the same on MySQL, PostgreSQL and SQLite.
            $term = '%'.str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $f['search']).'%';
            $query->where(fn (Builder $q) => $q
                ->whereRaw("title LIKE ? ESCAPE '!'", [$term])
                ->orWhereRaw("description LIKE ? ESCAPE '!'", [$term]));
        }

        if (filled($f['status'] ?? null)) {
            $query->whereIn('status', explode(',', $f['status']));
        }

        if (filled($f['priority'] ?? null)) {
            $query->whereIn('priority', array_map('intval', explode(',', $f['priority'])));
        }

        if (filled($f['category_id'] ?? null)) {
            $f['category_id'] === 'none'
                ? $query->whereNull('category_id')
                : $query->where('category_id', (int) $f['category_id']);
        }

        if (filled($f['label_id'] ?? null)) {
            $query->whereHas('labels', fn (Builder $q) => $q->whereKey((int) $f['label_id']));
        }

        match ($f['due'] ?? null) {
            'today'     => $query->whereDate('due_date', $today),
            'overdue'   => $query->overdue($today),
            'upcoming'  => $query->whereDate('due_date', '>', $today),
            'week'      => $query->whereBetween('due_date', [$today, Carbon::parse($today)->addDays(6)->toDateString()]),
            'scheduled' => $query->whereNotNull('due_date'),
            'none'      => $query->whereNull('due_date'),
            default     => null,
        };

        if (filled($f['date_from'] ?? null)) {
            $query->whereDate('due_date', '>=', $f['date_from']);
        }

        if (filled($f['date_to'] ?? null)) {
            $query->whereDate('due_date', '<=', $f['date_to']);
        }
    }

    private function applySort(Builder $query, string $sort, ?string $direction): void
    {
        $direction ??= $sort === 'created_at' ? 'desc' : 'asc';

        match ($sort) {
            'due_date' => $query
                ->orderByRaw('CASE WHEN due_date IS NULL THEN 1 ELSE 0 END')
                ->orderBy('due_date', $direction)
                ->orderByRaw('CASE WHEN due_time IS NULL THEN 1 ELSE 0 END')
                ->orderBy('due_time', $direction),
            'priority' => $query->orderBy('priority', $direction === 'asc' ? 'desc' : 'asc'), // "asc" = most important first
            default    => $query->orderBy($sort, $direction),
        };

        $query->orderBy('id', $sort === 'created_at' ? $direction : 'asc');
    }

    public function nextPosition(User $user, string $status): int
    {
        return (int) $user->tasks()->where('status', $status)->max('position') + 1;
    }
}

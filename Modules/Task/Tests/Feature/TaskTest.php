<?php

use Illuminate\Support\Facades\Mail;
use Modules\Category\Models\Category;
use Modules\Label\Models\Label;
use Modules\Task\Mail\TaskReminderMail;
use Modules\Task\Models\Subtask;
use Modules\Task\Models\Task;

use function Pest\Laravel\artisan;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\patchJson;
use function Pest\Laravel\postJson;

describe('create & read', function () {
    it('creates a task with category, labels and subtasks', function () {
        $user = login();
        $category = Category::factory()->for($user)->create();
        $labels = Label::factory()->for($user)->count(2)->create();

        postJson(api('tasks'), [
            'title' => '  Ship v1  ',
            'description' => 'Release notes too',
            'priority' => 4,
            'category_id' => $category->id,
            'label_ids' => $labels->pluck('id')->all(),
            'due_date' => '2030-01-15',
            'due_time' => '09:30',
            'subtasks' => ['Write changelog', 'Tag release'],
        ])
            ->assertCreated()
            ->assertJsonPath('data.title', 'Ship v1')
            ->assertJsonPath('data.status', 'todo')
            ->assertJsonPath('data.priority', 4)
            ->assertJsonPath('data.due_date', '2030-01-15')
            ->assertJsonPath('data.due_time', '09:30')
            ->assertJsonPath('data.category.id', $category->id)
            ->assertJsonCount(2, 'data.labels')
            ->assertJsonCount(2, 'data.subtasks')
            ->assertJsonPath('data.subtasks_count', 2)
            ->assertJsonPath('data.completed_subtasks_count', 0)
            ->assertJsonPath('data.is_overdue', false);
    });

    it('only needs a title and appends to the end of the column', function () {
        $user = login();
        Task::factory()->for($user)->create(['position' => 7]);

        postJson(api('tasks'), ['title' => 'Quick one'])
            ->assertCreated()
            ->assertJsonPath('data.priority', 2)
            ->assertJsonPath('data.position', 8)
            ->assertJsonPath('data.category', null);
    });

    it("refuses another user's category or labels", function () {
        login();

        postJson(api('tasks'), [
            'title' => 'x',
            'category_id' => Category::factory()->create()->id,
            'label_ids' => [Label::factory()->create()->id],
        ])->assertJsonValidationErrors(['category_id', 'label_ids.0']);
    });

    it('validates fields', function () {
        login();

        postJson(api('tasks'), ['title' => '', 'status' => 'nope', 'priority' => 9, 'due_date' => '15/01/2030', 'due_time' => '25:00'])
            ->assertJsonValidationErrors(['title', 'status', 'priority', 'due_date', 'due_time']);
    });

    it("never shows other users' tasks", function () {
        $user = login();
        Task::factory()->for($user)->create();
        $foreign = Task::factory()->create();

        getJson(api('tasks'))->assertJsonCount(1, 'data')->assertJsonPath('meta.total', 1);
        getJson(api("tasks/{$foreign->id}"))->assertNotFound();
        patchJson(api("tasks/{$foreign->id}"), ['title' => 'mine now'])->assertNotFound();
        patchJson(api("tasks/{$foreign->id}/toggle"))->assertNotFound();
        deleteJson(api("tasks/{$foreign->id}"))->assertNotFound();
        postJson(api("tasks/{$foreign->id}/subtasks"), ['title' => 'x'])->assertNotFound();
    });
});

describe('update', function () {
    it('patches only the given fields and syncs labels', function () {
        $user = login();
        $task = Task::factory()->for($user)->create(['title' => 'Old', 'priority' => 1]);
        $label = Label::factory()->for($user)->create();

        patchJson(api("tasks/{$task->id}"), ['priority' => 3, 'label_ids' => [$label->id]])
            ->assertOk()
            ->assertJsonPath('data.title', 'Old')
            ->assertJsonPath('data.priority', 3)
            ->assertJsonPath('data.labels.0.id', $label->id);

        patchJson(api("tasks/{$task->id}"), ['label_ids' => []])->assertJsonCount(0, 'data.labels');
    });

    it('sets and clears completed_at with the status', function () {
        $user = login();
        $task = Task::factory()->for($user)->create();

        $done = patchJson(api("tasks/{$task->id}"), ['status' => 'done'])->assertOk();
        expect($done->json('data.completed_at'))->not->toBeNull();

        patchJson(api("tasks/{$task->id}/toggle"))->assertJsonPath('data.status', 'todo')->assertJsonPath('data.completed_at', null);
        patchJson(api("tasks/{$task->id}/toggle"))->assertJsonPath('data.status', 'done');
    });

    it('clears the time together with the date', function () {
        $user = login();
        $task = Task::factory()->for($user)->create(['due_date' => '2030-01-01', 'due_time' => '10:00']);

        patchJson(api("tasks/{$task->id}"), ['due_date' => null])
            ->assertJsonPath('data.due_date', null)
            ->assertJsonPath('data.due_time', null);
    });

    it('deletes a task', function () {
        $user = login();
        $task = Task::factory()->for($user)->create();

        deleteJson(api("tasks/{$task->id}"))->assertNoContent();
        expect(Task::count())->toBe(0);
    });
});

describe('listing', function () {
    beforeEach(function () {
        $this->travelTo('2030-06-15 12:00:00');
        $this->user = login();
    });

    it('filters by due window', function (string $due, int $expected) {
        Task::factory()->for($this->user)->dueOn('2030-06-15')->create();         // today
        Task::factory()->for($this->user)->dueOn('2030-06-10')->create();         // overdue
        Task::factory()->for($this->user)->dueOn('2030-06-10')->done()->create(); // done: not overdue
        Task::factory()->for($this->user)->dueOn('2030-06-18')->create();         // upcoming, this week
        Task::factory()->for($this->user)->dueOn('2030-07-30')->create();         // upcoming
        Task::factory()->for($this->user)->dueOn(null)->create();                 // no date

        getJson(api("tasks?due={$due}"))->assertOk()->assertJsonPath('meta.total', $expected);
    })->with([
        ['today', 1],
        ['overdue', 1],
        ['upcoming', 2],
        ['week', 2],
        ['scheduled', 5],
        ['none', 1],
    ]);

    it('filters by search, status, priority, category and label', function () {
        $cat = Category::factory()->for($this->user)->create();
        $label = Label::factory()->for($this->user)->create();
        $a = Task::factory()->for($this->user)->for($cat)->create(['title' => 'Buy 100% coffee', 'priority' => 4]);
        $a->labels()->attach($label);
        Task::factory()->for($this->user)->inProgress()->create(['title' => 'Write docs', 'priority' => 1]);
        Task::factory()->for($this->user)->done()->create(['title' => 'Coffee filter', 'priority' => 1]);

        getJson(api('tasks?search=coffee'))->assertJsonPath('meta.total', 2);
        getJson(api('tasks?search=100%25'))->assertJsonPath('meta.total', 1);
        getJson(api('tasks?status=todo,in_progress'))->assertJsonPath('meta.total', 2);
        getJson(api('tasks?priority=4'))->assertJsonPath('data.0.id', $a->id);
        getJson(api("tasks?category_id={$cat->id}"))->assertJsonPath('meta.total', 1);
        getJson(api('tasks?category_id=none'))->assertJsonPath('meta.total', 2);
        getJson(api("tasks?label_id={$label->id}"))->assertJsonPath('meta.total', 1);
        getJson(api('tasks?status=bogus'))->assertJsonValidationErrors('status');
    });

    it('filters a calendar range and sorts by due date with empty dates last', function () {
        Task::factory()->for($this->user)->dueOn(null)->create(['title' => 'none']);
        Task::factory()->for($this->user)->dueOn('2030-06-20')->create(['title' => 'later']);
        Task::factory()->for($this->user)->dueOn('2030-06-01')->create(['title' => 'early']);

        getJson(api('tasks?sort=due_date'))->assertJsonPath('data.0.title', 'early')->assertJsonPath('data.2.title', 'none');
        getJson(api('tasks?date_from=2030-06-01&date_to=2030-06-30'))->assertJsonPath('meta.total', 2);
    });

    it('uses the client timezone for "today"', function () {
        // 12:00 UTC on 2030-06-15 is already 2030-06-16 in Kiribati (UTC+14).
        Task::factory()->for($this->user)->dueOn('2030-06-16')->create();

        getJson(api('tasks?due=today'))->assertJsonPath('meta.total', 0);
        getJson(api('tasks?due=today'), ['X-Timezone' => 'Pacific/Kiritimati'])->assertJsonPath('meta.total', 1);
    });

    it('paginates', function () {
        Task::factory()->for($this->user)->count(5)->create();

        getJson(api('tasks?per_page=2&page=3'))->assertJsonCount(1, 'data')->assertJsonPath('meta.last_page', 3);
        getJson(api('tasks?per_page=500'))->assertJsonValidationErrors('per_page');
    });
});

describe('kanban', function () {
    it('reorders and moves tasks between columns', function () {
        $user = login();
        [$a, $b] = Task::factory()->for($user)->count(2)->create();

        postJson(api('tasks/reorder'), ['tasks' => [
            ['id' => $b->id, 'status' => 'in_progress', 'position' => 0],
            ['id' => $a->id, 'status' => 'done', 'position' => 0],
        ]])->assertNoContent();

        expect($b->fresh()->status->value)->toBe('in_progress')
            ->and($a->fresh()->completed_at)->not->toBeNull();
    });

    it("can't reorder another user's tasks", function () {
        login();
        $foreign = Task::factory()->create();

        postJson(api('tasks/reorder'), ['tasks' => [['id' => $foreign->id, 'status' => 'done', 'position' => 0]]])
            ->assertJsonValidationErrors('tasks.0.id');
    });
});

describe('subtasks', function () {
    it('adds, updates and removes subtasks', function () {
        $user = login();
        $task = Task::factory()->for($user)->create();

        $id = postJson(api("tasks/{$task->id}/subtasks"), ['title' => 'Step one'])
            ->assertCreated()
            ->assertJsonPath('data.is_completed', false)
            ->json('data.id');

        patchJson(api("subtasks/{$id}"), ['is_completed' => true])->assertJsonPath('data.is_completed', true);
        getJson(api("tasks/{$task->id}"))->assertJsonPath('data.completed_subtasks_count', 1);

        deleteJson(api("subtasks/{$id}"))->assertNoContent();
        expect(Subtask::count())->toBe(0);
    });

    it("hides other users' subtasks", function () {
        login();
        $foreign = Subtask::factory()->create();

        patchJson(api("subtasks/{$foreign->id}"), ['title' => 'x'])->assertNotFound();
        deleteJson(api("subtasks/{$foreign->id}"))->assertNotFound();
    });
});

describe('reminders', function () {
    it('e-mails due reminders once', function () {
        Mail::fake();
        $user = login();
        $due = Task::factory()->for($user)->create(['remind_at' => now()->subMinute()]);
        Task::factory()->for($user)->create(['remind_at' => now()->addHour()]);
        Task::factory()->for($user)->done()->create(['remind_at' => now()->subMinute()]);

        artisan('tasks:send-reminders')->assertSuccessful();
        artisan('tasks:send-reminders')->assertSuccessful();

        Mail::assertQueuedCount(1);
        Mail::assertQueued(TaskReminderMail::class, fn ($m) => $m->task->is($due) && $m->hasTo($user->email));
    });

    it('re-arms the reminder when its time changes', function () {
        $user = login();
        $task = Task::factory()->for($user)->create(['remind_at' => now()->subMinute()]);
        artisan('tasks:send-reminders');

        patchJson(api("tasks/{$task->id}"), ['remind_at' => now()->addDay()->toIso8601String()]);

        expect($task->fresh()->reminded_at)->toBeNull();
    });
});

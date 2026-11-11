<?php

namespace Modules\Task\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Modules\Task\Mail\TaskReminderMail;
use Modules\Task\Models\Task;

class SendTaskReminders extends Command
{
    protected $signature = 'tasks:send-reminders';

    protected $description = 'E-mail reminders for open tasks whose reminder time has passed';

    public function handle(): int
    {
        $sent = 0;

        Task::query()
            ->open()
            ->whereNotNull('remind_at')
            ->whereNull('reminded_at')
            ->where('remind_at', '<=', now())
            ->with('user')
            ->chunkById(100, function ($tasks) use (&$sent) {
                foreach ($tasks as $task) {
                    Mail::to($task->user)->queue(new TaskReminderMail($task));

                    // Bypass the model's `saving` hook, which would reset `reminded_at`.
                    $task->newQuery()->whereKey($task->id)->update(['reminded_at' => now()]);
                    $sent++;
                }
            });

        $this->components->info("{$sent} reminder(s) queued.");

        return self::SUCCESS;
    }
}

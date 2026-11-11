<?php

namespace Modules\Task\Providers;

use Illuminate\Support\Facades\Gate;
use Modules\Common\Policies\OwnedByUserPolicy;
use Modules\Common\Providers\ModuleServiceProvider;
use Modules\Task\Console\SendTaskReminders;
use Modules\Task\Models\Subtask;
use Modules\Task\Models\Task;
use Modules\Task\Policies\SubtaskPolicy;

class TaskServiceProvider extends ModuleServiceProvider
{
    protected string $module = 'Task';

    protected function bootModule(): void
    {
        Gate::policy(Task::class, OwnedByUserPolicy::class);
        Gate::policy(Subtask::class, SubtaskPolicy::class);

        if ($this->app->runningInConsole()) {
            $this->commands([SendTaskReminders::class]);
        }
    }
}

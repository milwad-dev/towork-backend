<?php

namespace Modules\Label\Providers;

use Illuminate\Support\Facades\Gate;
use Modules\Common\Policies\OwnedByUserPolicy;
use Modules\Common\Providers\ModuleServiceProvider;
use Modules\Label\Models\Label;

class LabelServiceProvider extends ModuleServiceProvider
{
    protected string $module = 'Label';

    protected function bootModule(): void
    {
        Gate::policy(Label::class, OwnedByUserPolicy::class);
    }
}

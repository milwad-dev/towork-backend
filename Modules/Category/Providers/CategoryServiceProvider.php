<?php

namespace Modules\Category\Providers;

use Illuminate\Support\Facades\Gate;
use Modules\Category\Models\Category;
use Modules\Common\Policies\OwnedByUserPolicy;
use Modules\Common\Providers\ModuleServiceProvider;

class CategoryServiceProvider extends ModuleServiceProvider
{
    protected string $module = 'Category';

    protected function bootModule(): void
    {
        Gate::policy(Category::class, OwnedByUserPolicy::class);
    }
}

<?php

use App\Providers\AppServiceProvider;
use Modules\Auth\Providers\AuthServiceProvider;
use Modules\Category\Providers\CategoryServiceProvider;
use Modules\Common\Providers\CommonServiceProvider;
use Modules\Dashboard\Providers\DashboardServiceProvider;
use Modules\Label\Providers\LabelServiceProvider;
use Modules\Task\Providers\TaskServiceProvider;
use Modules\User\Providers\UserServiceProvider;

return [
    AppServiceProvider::class,

    // Modules
    CommonServiceProvider::class,
    UserServiceProvider::class,
    AuthServiceProvider::class,
    CategoryServiceProvider::class,
    LabelServiceProvider::class,
    TaskServiceProvider::class,
    DashboardServiceProvider::class,
];

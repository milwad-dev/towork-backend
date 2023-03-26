<?php

namespace Modules\Auth\Tests\Feature\Password;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Models\ResetCodePassword;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);
uses(TestCase::class);

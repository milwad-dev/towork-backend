<?php

namespace Modules\Category\Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Category\Models\Category;
use Modules\User\Models\User;
use Tests\TestCase;
use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas};
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertTrue;

/*
 * Use refresh database for truncate database for each test.
 */
uses(RefreshDatabase::class);

/**
 * Use Testcase to add some requirements.
 */
uses(TestCase::class);

test('test login user can store category', function () {
    $user = User::factory()->create();
    $title = 'Implicit Title';

    $this->actingAs($user)->post(route('categories.store'), [
        'title' => $title
    ]);

    assertDatabaseCount('categories', 1);
    assertDatabaseHas('categories', ['title' => $title]);
});




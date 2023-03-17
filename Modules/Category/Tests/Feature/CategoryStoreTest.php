<?php

namespace Modules\Category\Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Models\User;
use Tests\TestCase;

use function Pest\Laravel\{assertDatabaseCount, assertDatabaseHas, assertDatabaseMissing};

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
        'title' => $title,
    ])->assertOk();

    assertDatabaseCount('categories', 1);
    assertDatabaseHas('categories', ['title' => $title]);
});

test('test guest user can not store category', function () {
    $title = 'Implicit Title';

    $this->post(route('categories.store'), [
        'title' => $title,
    ])->assertRedirect();

    assertDatabaseCount('categories', 0);
    assertDatabaseMissing('categories', ['title' => $title]);
});

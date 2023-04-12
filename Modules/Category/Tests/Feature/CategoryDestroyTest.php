<?php

namespace Modules\Category\Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Category\Models\Category;
use Modules\User\Models\User;
use Tests\TestCase;
use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas, assertDatabaseMissing, deleteJson};

/*
 * Use refresh database for truncate database for each test.
 */
uses(RefreshDatabase::class);

/**
 * Use Testcase to add some requirements.
 */
uses(TestCase::class);

test('test login user can delete category', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create(['user_id' => $user->id]);

    actingAs($user)->deleteJson(route('categories.destroy', $category->id))->assertNoContent();

    assertDatabaseCount('categories', 0);
    assertDatabaseMissing('categories', ['title' => $category->title]);
});

test('test guest user can not delete category', function () {
    $category = Category::factory()->create();

    deleteJson(route('categories.destroy', $category->id))->assertUnauthorized();

    assertDatabaseCount('categories', 1);
    assertDatabaseHas('categories', ['title' => $category->title]);
});

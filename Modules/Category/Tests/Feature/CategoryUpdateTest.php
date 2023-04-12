<?php

namespace Modules\Category\Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Category\Models\Category;
use Modules\User\Models\User;
use Tests\TestCase;

use function Pest\Laravel\{actingAs, assertDatabaseCount, assertDatabaseHas, assertDatabaseMissing, patchJson};

/*
 * Use refresh database for truncate database for each test.
 */
uses(RefreshDatabase::class);

/**
 * Use Testcase to add some requirements.
 */
uses(TestCase::class);

test('test login user can update category', function () {
    $user = User::factory()->create();
    $category = createCategory($user->id);

    $title = 'Implicit Title';

    actingAs($user)->patchJson(route('categories.update', $category->id), [
        'title' => $title,
    ])->assertOk();

    assertDatabaseCount('categories', 1);
    assertDatabaseHas('categories', ['title' => $title]);
});

test('test guest user can not update category', function () {
    $user = User::factory()->create();
    $category = createCategory($user->id);

    $title = 'Implicit Title';

    patchJson(route('categories.update', $category->id), [
        'title' => $title,
    ])->assertUnauthorized();

    assertDatabaseCount('categories', 1);
    assertDatabaseMissing('categories', ['title' => $title]);
    assertDatabaseHas('categories', ['title' => $category->title]);
});

/**
 * Create category.
 *
 * @param int $userId
 *
 * @return Category
 */
function createCategory(int $userId)
{
    return Category::create([
        'title'   => '::title::',
        'user_id' => $userId,
    ]);
}

<?php
 namespace Modules\Category\Tests\Feature\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Category\Models\Category;
use Modules\User\Models\User;
use Tests\TestCase;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
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

test('test insert data', function () {
    $data = Category::factory()->make()->toArray();

    Category::create($data);

    assertDatabaseCount('categories' , 1);
    assertDatabaseHas('categories' , $data);
});

test('test category relation with user', function () {
    $user = User::factory()->create();
    $category = Category::factory()->for($user)->create();

    assertInstanceOf(User::class , $category->user );
    assertTrue(isset($category->user->id));
});


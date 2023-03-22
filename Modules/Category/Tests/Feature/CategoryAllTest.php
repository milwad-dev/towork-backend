<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Modules\Category\Models\Category;
use Modules\User\Models\User;
use Tests\TestCase;

/*
 * Use refresh database for truncate database for each test.
 */
uses(RefreshDatabase::class);

/**
 * Use Testcase to add some requirements.
 */
uses(TestCase::class);

test('test login user can see all own categories', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson(route('categories.index'));
    $response->assertStatus(200);
    $response->assertJsonStructure([
        'data' => [
            'categories',
        ],
        'status',
    ]);
});

test('test guest user can see all own categories', function () {
    $this->getJson(route('categories.index'))->assertUnauthorized();
});

test('test field of categories table is correct', function () {
    $has = Schema::hasColumns((new Category)->getTable(), [
        'id', 'title', 'user_id', 'created_at', 'updated_at',
    ]);

    $this->assertTrue($has, 1);
});

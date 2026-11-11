<?php

use Modules\Label\Models\Label;
use Modules\Task\Models\Task;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('manages labels', function () {
    login();

    $id = postJson(api('labels'), ['name' => 'urgent', 'color' => 'red'])
        ->assertCreated()
        ->assertJsonPath('data.color', 'red')
        ->json('data.id');

    putJson(api("labels/{$id}"), ['name' => 'later'])->assertOk()->assertJsonPath('data.name', 'later');
    getJson(api('labels'))->assertJsonCount(1, 'data');
    deleteJson(api("labels/{$id}"))->assertNoContent();
});

it('detaches a deleted label from its tasks', function () {
    $user = login();
    $label = Label::factory()->for($user)->create();
    $task = Task::factory()->for($user)->create();
    $task->labels()->attach($label);

    deleteJson(api("labels/{$label->id}"))->assertNoContent();

    expect($task->labels()->count())->toBe(0);
});

it("hides other users' labels", function () {
    login();
    $foreign = Label::factory()->create();

    putJson(api("labels/{$foreign->id}"), ['name' => 'x'])->assertNotFound();
});

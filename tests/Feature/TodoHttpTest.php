<?php

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user, 'api');
});

it('creates and lists todos', function () {
    $payload = ['title' => 'Write tests'];

    $this->postJson('/api/v1/todos', $payload)
    ->assertCreated()
    ->assertJsonFragment(['title' => 'Write tests']);

    $this->getJson('/api/v1/todos')
    ->assertOk()
    ->assertJsonFragment(['title' => 'Write tests']);
});

it('updates and toggles a todo', function () {
    $todo = Todo::factory()->create(['is_completed' => false]);

    $this->patchJson("/api/v1/todos/{$todo->id}", ['title' => 'New'])
    ->assertOk()
    ->assertJsonFragment(['title' => 'New']);

    $this->patchJson("/api/v1/todos/{$todo->id}/toggle")
    ->assertOk()
    ->assertJsonFragment(['is_completed' => true]);
});

it('deletes a todo', function () {
    $todo = Todo::factory()->create();

    $this->deleteJson("/api/v1/todos/{$todo->id}")
    ->assertNoContent();

    $this->getJson("/api/v1/todos/{$todo->id}")
    ->assertNotFound();
});

<?php

use App\Models\Todo;
use App\Repositories\TodoRepository;
use App\Services\TodoService;
use Mockery as m;

it('creates a todo', function () {
    $repo = m::mock(TodoRepository::class);
    $service = new TodoService($repo);

    $input = ['title' => ' Study '];
    $expected = new Todo(['title' => 'Study']);

    $repo->shouldReceive('create')
    ->once()
    ->with(['title' => 'Study'])
    ->andReturn($expected);

    $created = $service->create($input);
    expect($created->title)->toBe('Study');
});

it('toggles completion', function () {
    $repo = m::mock(TodoRepository::class);
    $service = new TodoService($repo);

    $todo = new Todo(['title' => 'Task', 'is_completed' => false]);

    $repo->shouldReceive('find')
    ->once()
    ->with(1)
    ->andReturn($todo);

    $repo->shouldReceive('toggle')
    ->once()
    ->with($todo)
    ->andReturn(new Todo(['title' => 'Task', 'is_completed' => true]));

    $toggled = $service->toggle(1);
    expect($toggled->is_completed)->toBeTrue();
});

<?php

namespace App\Repositories;

use App\Models\Todo;

class TodoRepository
{
    public function paginate(int $perPage = 15)
    {
        return Todo::query()->latest()->paginate($perPage);
    }

    public function find(int $id): Todo
    {
        return Todo::query()->findOrFail($id);
    }

    public function create(array $data): Todo
    {
        return Todo::create($data);
    }

    public function update(Todo $todo, array $data): Todo
    {
        $todo->update($data);
        return $todo;
    }

    public function delete(Todo $todo): void
    {
        $todo->delete();
    }

    public function toggle(Todo $todo): Todo
    {
        $todo->is_completed = ! $todo->is_completed;
        $todo->save();
        return $todo;
    }
}

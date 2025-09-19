<?php

namespace App\Services;

use App\Models\Todo;
use App\Repositories\TodoRepository;

class TodoService
{
    public function __construct(private TodoRepository $repo) {}

    public function list(int $perPage = 15)
    {
        return $this->repo->paginate($perPage);
    }

    public function get(int $id): Todo
    {
        return $this->repo->find($id);
    }

    public function create(array $data): Todo
    {
        $data['title'] = trim($data['title']);
        return $this->repo->create($data);
    }

    public function update(int $id, array $data): Todo
    {
        $todo = $this->repo->find($id);
        if (isset($data['title'])) {
            $data['title'] = trim($data['title']);
        }

        return $this->repo->update($todo, $data);
    }

    public function delete(int $id): void
    {
        $todo = $this->repo->find($id);
        $this->repo->delete($todo);
    }

    public function toggle(int $id): Todo
    {
        $todo = $this->repo->find($id);
        return $this->repo->toggle($todo);
    }
}

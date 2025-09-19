<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Models\Todo;
use App\Services\TodoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function __construct(private TodoService $service) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 15);
        return response()->json($this->service->list($perPage));
    }

    public function show(int $id): JsonResponse
    {
        return response()->json($this->service->get($id));
    }

    public function store(StoreTodoRequest $request): JsonResponse
    {
        $todo = $this->service->create($request->validated());
        return response()->json($todo, 201);
    }

    public function update(UpdateTodoRequest $request, int $id): JsonResponse
    {
        $todo = $this->service->update($id, $request->validated());
        return response()->json($todo);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return response()->json(null, 204);
    }

    public function toggle(Todo $todo): JsonResponse
    {
        $toggled = $this->service->toggle($todo->id);
        return response()->json($toggled);
    }
}

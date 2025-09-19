<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use App\Models\Todo;
use App\Services\TodoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class TodoController extends Controller
{
    public function __construct(private TodoService $service) {}

    /**
     * @OA\Get(
     *   path="/api/v1/todos",
     *   summary="Listar tarefas (paginado)",
     *   tags={"Todos"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", default=15)),
     *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/PaginatedTodos")),
     *   @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/MessageResponse"))
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 15);
        return response()->json($this->service->list($perPage));
    }

    /**
     * @OA\Get(
     *   path="/api/v1/todos/{id}",
     *   summary="Buscar tarefa por ID",
     *   tags={"Todos"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/Todo")),
     *   @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/MessageResponse")),
     *   @OA\Response(response=404, description="Not found", @OA\JsonContent(ref="#/components/schemas/MessageResponse"))
     * )
     */
    public function show(int $id): JsonResponse
    {
        return response()->json($this->service->get($id));
    }

    /**
     * @OA\Post(
     *   path="/api/v1/todos",
     *   summary="Criar tarefa",
     *   tags={"Todos"},
     *   security={{"bearerAuth": {}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       type="object",
     *       required={"title"},
     *       @OA\Property(property="title", type="string", example="Study"),
     *       @OA\Property(property="description", type="string", nullable=true, example="Read docs"),
     *       @OA\Property(property="due_date", type="string", format="date", nullable=true, example="2025-09-22")
     *     )
     *   ),
     *   @OA\Response(response=201, description="Criado", @OA\JsonContent(ref="#/components/schemas/Todo")),
     *   @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/MessageResponse")),
     *   @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ValidationError"))
     * )
     */
    public function store(StoreTodoRequest $request): JsonResponse
    {
        $todo = $this->service->create($request->validated());
        return response()->json($todo, 201);
    }

    /**
     * @OA\Patch(
     *   path="/api/v1/todos/{id}",
     *   summary="Atualizar tarefa",
     *   tags={"Todos"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(
     *     @OA\JsonContent(
     *       type="object",
     *       @OA\Property(property="title", type="string"),
     *       @OA\Property(property="description", type="string", nullable=true),
     *       @OA\Property(property="is_completed", type="boolean"),
     *       @OA\Property(property="due_date", type="string", format="date", nullable=true)
     *     )
     *   ),
     *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/Todo")),
     *   @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/MessageResponse")),
     *   @OA\Response(response=404, description="Not found", @OA\JsonContent(ref="#/components/schemas/MessageResponse"))
     * )
     */
    public function update(UpdateTodoRequest $request, int $id): JsonResponse
    {
        $todo = $this->service->update($id, $request->validated());
        return response()->json($todo);
    }

    /**
     * @OA\Delete(
     *   path="/api/v1/todos/{id}",
     *   summary="Remover tarefa",
     *   tags={"Todos"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=204, description="Sem conteúdo"),
     *   @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/MessageResponse")),
     *   @OA\Response(response=404, description="Not found", @OA\JsonContent(ref="#/components/schemas/MessageResponse"))
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return response()->json(null, 204);
    }

    /**
     * @OA\Patch(
     *   path="/api/v1/todos/{id}/toggle",
     *   summary="Marcar/desmarcar tarefa",
     *   tags={"Todos"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/Todo")),
     *   @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/MessageResponse")),
     *   @OA\Response(response=404, description="Not found", @OA\JsonContent(ref="#/components/schemas/MessageResponse"))
     * )
     */
    public function toggle(Todo $todo): JsonResponse
    {
        $toggled = $this->service->toggle($todo->id);
        return response()->json($toggled);
    }
}

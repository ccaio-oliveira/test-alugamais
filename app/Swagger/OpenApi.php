<?php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *   title="To-Do API",
 *   version="1.0.0",
 *   description="CRUD de tarefas com toggle de conclusão (Starter Kit + JWT)"
 * )
 *
 * @OA\Server(
 *   url="http://localhost",
 *   description="Localhost (php artisan serve ou Docker)"
 * )
 *
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="JWT"
 * )
 *
 * @OA\Schema(
 *   schema="ValidationError",
 *   type="object",
 *   @OA\Property(property="message", type="string", example="The given data was invalid."),
 *   @OA\Property(property="errors", type="object",
 *     additionalProperties=@OA\Schema(type="array", @OA\Items(type="string")),
 *     example={"title":{"The title field is required."}}
 *   )
 * )
 *
 * @OA\Schema(
 *   schema="MessageResponse",
 *   type="object",
 *   @OA\Property(property="message", type="string", example="Logged out successfully.")
 * )
 *
 * @OA\Schema(
 *   schema="Todo",
 *   type="object",
 *   required={"id","title","is_completed","created_at","updated_at"},
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="title", type="string", example="Write tests"),
 *   @OA\Property(property="description", type="string", nullable=true, example="Cover service and HTTP"),
 *   @OA\Property(property="is_completed", type="boolean", example=false),
 *   @OA\Property(property="due_date", type="string", format="date", nullable=true, example="2025-09-30"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *   schema="PaginatedTodos",
 *   type="object",
 *   @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Todo")),
 *   @OA\Property(property="links", type="object",
 *     @OA\Property(property="first", type="string", nullable=true),
 *     @OA\Property(property="last", type="string", nullable=true),
 *     @OA\Property(property="prev", type="string", nullable=true),
 *     @OA\Property(property="next", type="string", nullable=true)
 *   ),
 *   @OA\Property(property="meta", type="object",
 *     @OA\Property(property="current_page", type="integer"),
 *     @OA\Property(property="from", type="integer", nullable=true),
 *     @OA\Property(property="last_page", type="integer"),
 *     @OA\Property(property="path", type="string"),
 *     @OA\Property(property="per_page", type="integer"),
 *     @OA\Property(property="to", type="integer", nullable=true),
 *     @OA\Property(property="total", type="integer")
 *   )
 * )
 *
 * @OA\Schema(
 *   schema="User",
 *   type="object",
 *   required={"id","name","email","created_at","updated_at"},
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="name", type="string", example="Caio"),
 *   @OA\Property(property="email", type="string", format="email", example="caio@example.com"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *   schema="AuthLoginRequest",
 *   type="object",
 *   required={"email","password"},
 *   @OA\Property(property="email", type="string", format="email", example="caio@example.com"),
 *   @OA\Property(property="password", type="string", example="secret123")
 * )
 *
 * @OA\Schema(
 *   schema="AuthRegisterRequest",
 *   type="object",
 *   required={"name","email","password","password_confirmation"},
 *   @OA\Property(property="name", type="string", example="Caio"),
 *   @OA\Property(property="email", type="string", format="email", example="caio@example.com"),
 *   @OA\Property(property="password", type="string", example="secret123"),
 *   @OA\Property(property="password_confirmation", type="string", example="secret123")
 * )
 *
 * @OA\Schema(
 *   schema="AuthResponse",
 *   type="object",
 *   @OA\Property(property="data", type="object",
 *     @OA\Property(property="user", ref="#/components/schemas/User"),
 *     @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOi..."),
 *     @OA\Property(property="token_type", type="string", example="bearer"),
 *     @OA\Property(property="expires_in", type="integer", example=3600)
 *   )
 * )
 */
final class OpenApi {}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\Api\User\UserResource;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class AuthController extends BaseApiController
{
    /**
     * AuthController constructor.
     */
    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    /**
     * @OA\Post(
     *   path="/api/v1/auth/register",
     *   summary="Registrar usuário",
     *   tags={"Auth"},
     *   @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AuthRegisterRequest")),
     *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/AuthResponse")),
     *   @OA\Response(response=422, description="Validation error", @OA\JsonContent(ref="#/components/schemas/ValidationError"))
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $this->authService->register($request->validated());

        return $this->successResponse($data);
    }

    /**
     * @OA\Post(
     *   path="/api/v1/auth/login",
     *   summary="Login",
     *   tags={"Auth"},
     *   @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/AuthLoginRequest")),
     *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/AuthResponse")),
     *   @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/MessageResponse"))
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $this->authService->login($request->validated());

        return $this->successResponse($data);
    }

    /**
     * @OA\Get(
     *   path="/api/v1/auth/me",
     *   summary="Usuário autenticado",
     *   tags={"Auth"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/User")),
     *   @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/MessageResponse"))
     * )
     */
    public function me(): JsonResponse
    {
        $user = $this->authService->me();

        return $this->successResponse(new UserResource($user));
    }

    /**
     * @OA\Get(
     *   path="/api/v1/auth/refresh",
     *   summary="Refresh token",
     *   tags={"Auth"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/AuthResponse")),
     *   @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/MessageResponse"))
     * )
     */
    public function refresh(): JsonResponse
    {
        $token = $this->authService->refresh();

        return $this->successResponse([
            'token' => $token,
            'token_type' => 'bearer',
        ]);
    }

    /**
     * @OA\Get(
     *   path="/api/v1/auth/logout",
     *   summary="Logout",
     *   tags={"Auth"},
     *   security={{"bearerAuth": {}}},
     *   @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/MessageResponse")),
     *   @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/MessageResponse"))
     * )
     */
    public function logout(): JsonResponse
    {
        $this->authService->logout();

        return $this->successResponse(['message' => 'Successfully logged out']);
    }
}

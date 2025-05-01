<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @param UserService $userService
     */
    public function __construct(
        private readonly UserService $userService
    )
    {
    }

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'sometimes|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:8',
            'steamid' => 'sometimes|string|min:8|unique:users',
            'profile_url' => 'sometimes|string|min:8|unique:users',
            'avatar' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
        ]);
        try {
            $user = $this->userService->create($data);
            return response()->json($user, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao criar conta.', 'error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST);
        }

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): ?\Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);
        $user = User::query()
            ->where('username', $data['username'])
            ->orWhere('email', $data['username'])
            ->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Credenciais inválidas!'],
                Response::HTTP_UNAUTHORIZED);
        } else {
            $user->tokens()->delete();
            $token = $user->createToken('auth-token')->plainTextToken;
            return response()->json(['token' => $token, 'user' => $user], Response::HTTP_OK);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse|null
     */
    public function logout(Request $request): ?\Illuminate\Http\JsonResponse
    {
        try {
            if (!$request->user()) {
                return response()->json(['message' => 'Usuário não autenticado!'], Response::HTTP_UNAUTHORIZED);
            }
            auth()->user()->tokens()->delete();
            return response()->json(['message' => 'Logout realizado com sucesso!'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao fazer logout!', 'error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST);
        }
    }
}

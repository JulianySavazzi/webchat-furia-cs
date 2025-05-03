<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @param UserService $userService
     */
    public function __construct(
        private readonly UserService $userService
    )
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $data = $request->validate([
            'email' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255',
            'steamid' => 'nullable|string|max:255',
            'profile_url' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
        ]);
        try {
            $user = $this->userService->index($data);
            return response()->json($user, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao listar usuarios.', 'error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST);
        }
    }

    public function me()
    {
        try {
            return $this->userService->me();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao listar usuaario.', 'error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST);
        }
    }

    public function getAllUsernames()
    {
        try {
            return $this->userService->getAllUsernames();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao listar usuaario.', 'error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST);
        }
    }
}

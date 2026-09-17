<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Auth\AuthRegisterRequest;
use App\Http\Resources\AuthResource;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(AuthRegisterRequest $request)
    {
        try {
            $validasi = $request->validated();

            $data = $this->userService->registerUser($validasi);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'user' => new AuthResource($data['user']),
                    'token' => $data['token'],
                    'token_type' => 'bearer'
                ]
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'gagal registrasi, ' . $e->getMessage()
            ], 400);
        }
    }

    public function login(AuthLoginRequest $request)
    {
        try {
            $validasi = $request->validated();

            $data = $this->userService->loginUser($validasi);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'user' => new AuthResource($data['user']),
                    'token' => $data['token'],
                    'token_type' => 'bearer'
                ]
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'gagal login, ' . $e->getMessage()
            ], 500);
        }
    }

    public function logout()
    {
        try {
            $this->userService->logoutUser();

            return response()->json([
                'status' => 'success',
                'message' => 'berhasil logout'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'gagal logout, ' . $e->getMessage()
            ], 500);
        }
    }

    public function refresh()
    {
        try {
            $data = $this->userService->refreshUserToken();

            return response()->json([
                'status' => 'success',
                'data' => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'gagal refresh token, ' . $e->getMessage()
            ], 500);
        }
    }
}

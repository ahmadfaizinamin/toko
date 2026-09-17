<?php
namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInteface;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInteface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function registerUser(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);

        $token = JWTAuth::fromUser($user);

        return [
            'user' => $user,
            'token' => $token,
            'expires_in' => config('jwt.ttl') * 60
        ];
    }

    public function loginUser(array $data)
    {
        if (! $token = Auth::guard('api')->attempt($data)) {
            throw new Exception('Email atau Password salah');
        }

        $user = Auth::guard('api')->user();

        return [
            'user' => $user,
            'token' => $token,
            'expires_in' => config('jwt.ttl') * 60
        ];
    }

    public function logoutUser()
    {
        return Auth::guard('api')->logout();
    }

    public function refreshUserToken()
    {
        $token = JWTAuth::parseToken()->refresh();

        return [
            'token' => $token,
            'expires_in' => config('jwt.ttl') * 60
        ];
    }
}
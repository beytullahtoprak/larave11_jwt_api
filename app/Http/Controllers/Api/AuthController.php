<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Api\Auth\ResetPasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = JWTAuth::fromUser($user);

            return $this->sendSuccess(['token' => $token, 'user' => $user], 'User registered successfully', 201);
        } catch (\Exception $e) {
            return $this->sendError('Registration failed', [$e->getMessage()], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
                return $this->sendError('Invalid credentials', [], 401);
            }
            $user = JWTAuth::user();
            return $this->sendSuccess(['token' => $token, 'user' => $user], 'Login successful');
        } catch (\Exception $e) {
            return $this->sendError('Could not create token', [$e->getMessage()], 500);
        }
    }

    public function profile()
    {
        try {
            return $this->sendSuccess(Auth::user(), 'User profile fetched successfully');
        } catch (\Exception $e) {
            return $this->sendError('Could not fetch user data', [$e->getMessage()], 500);
        }
    }

    public function logout()
    {
        try {
            Auth::logout();
            return $this->sendSuccess([], 'Successfully logged out');
        } catch (\Exception $e) {
            return $this->sendError('Logout failed', [$e->getMessage()], 500);
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return $this->sendError('User not found', [], 404);
            }

            // Şifreyi güncelle
            $user->password = Hash::make($request->password);
            $user->save();

            return $this->sendSuccess([], 'Password reset successfully');
        } catch (\Exception $e) {
            return $this->sendError('Failed to reset password', [$e->getMessage()], 500);
        }
    }

    public function refreshToken()
    {
        try {
            $newToken = JWTAuth::refresh(JWTAuth::getToken());

            return $this->sendSuccess(['token' => $newToken], 'Token refreshed successfully');
        } catch (\Exception $e) {
            return $this->sendError('Could not refresh token', [$e->getMessage()], 500);
        }
    }
}

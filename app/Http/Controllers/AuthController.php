<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Login the user for SPA authentication
        Auth::guard('web')->login($user);

        return ApiResponse::success(
            'Registration successful',
            ['user' => new UserResource($user)],
            201
        );
    }

    /**
     * Login user.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if (! Auth::guard('web')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return ApiResponse::error('Invalid credentials', 401);
        }

        // Regenerate session if available
        // The EnsureFrontendRequestsAreStateful middleware should have initialized it
        if ($request->hasSession()) {
            $request->session()->regenerate();
        } elseif (session()->isStarted()) {
            session()->regenerate();
        }

        $user = Auth::guard('web')->user();

        return ApiResponse::success(
            'Login successful',
            ['user' => new UserResource($user)]
        );
    }

    /**
     * Get authenticated user.
     */
    public function user(Request $request): JsonResponse
    {
        return ApiResponse::success(
            'User retrieved successfully',
            ['user' => new UserResource($request->user())]
        );
    }

    /**
     * Logout user.
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();
        
        // Invalidate session if available
        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        } elseif (session()->isStarted()) {
            session()->invalidate();
            session()->regenerateToken();
        }

        return ApiResponse::success('Logout successful');
    }
}


<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validation Request Login
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid login credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User logged in successfully',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function register(Request $request)
    {
        // Validasi Request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nisn' => 'nullable|string|max:20|unique:users',
            'school_origin' => 'nulllable|string|max:255',
        ]);

        $user = User::create([
            'role_id' => 3, // Default role as student
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'nisn' => $request->nisn,
            'school_origin' => $request->school_origin,
            'is_active' => true,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function logout(Request $request)
    {
        // Hapus token user
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'User logged out successfully'
        ], 200);
    }
}

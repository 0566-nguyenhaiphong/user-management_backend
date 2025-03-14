<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json(['token' => $token]);
    }

    public function profile(Request $request)
    {
        return response()->json([
            'user' => auth('api')->user(),
            'token' => $request->bearerToken(),
        ]);
    }


    public function updateProfile(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if (Auth::user()->id !== $user->id && Auth::user()->role !== 'user') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $user->update($request->only(['name', 'email', 'password']));
        return response()->json(['message' => 'User updated successfully']);
    }
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json(['message' => 'Logged out successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}

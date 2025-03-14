<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $perPage = 5;
        $users = User::paginate($perPage);

        return response()->json([
            'users' => $users->items(),
            'total_pages' => $users->lastPage(),
            'current_page' => $users->currentPage()
        ]);
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,user',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
        ]);

        return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
    }

    public function show($id)
    {
        return response()->json(User::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if (Auth::user()->id !== $user->id && Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $user->update($request->only(['name', 'email']));
        return response()->json(['message' => 'User updated successfully']);
    }

    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}

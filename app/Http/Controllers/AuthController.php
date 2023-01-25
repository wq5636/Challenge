<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register(Request $request) {
        $attributes = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'password' => bcrypt($attributes['password']),
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'name' => $user->name,
            'token' => $token,
        ], 201);
    }


    public function login(Request $request) {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $token = $user->createToken('api_token')->plainTextToken;
            return response()->json([
                'name' => $user->name,
                'token' => $token,
            ], 200);
        } else {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
    }


    public function logout() {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}

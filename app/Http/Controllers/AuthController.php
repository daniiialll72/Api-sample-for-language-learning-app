<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\DatabaseConnection;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\v1\Auth\LoginRequest;
use App\Http\Requests\Api\v1\Auth\ConfirmRequest;
use Illuminate\Support\Facades\App;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|max:55',
                'family' => 'required|max:55',
                'username' => 'required|unique:users,username|max:55',
                // 'email' => 'required|max:55',
                'phone' => 'required|max:55',
                'password' => 'required',
                'languagemother_id' => 'required'
            ]);

            $validatedData['password'] = bcrypt($request->password);

            $user = User::create($validatedData);

            $accessToken = $user->createToken('authToken')->plainTextToken;

            return response(['user' => $user, 'access_token' => $accessToken]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $loginData = $request->validate([
                'username' => 'required',
                'password' => 'required'
            ]);
            // dd(Auth::attempt($loginData));
            if (!Auth::attempt($loginData)) {
                return response(['message' => 'Invalid Credentials']);
            }

            Auth::attempt($loginData);
            $accessToken = auth()->user()->createToken('authToken')->plainTextToken;

            return response(['user' => new UserResource(auth()->user()), 'access_token' => $accessToken]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            return response()->json([
                'status' => true,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], 500);
        }
    }
}

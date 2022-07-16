<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\DatabaseConnection;
use Illuminate\Support\Facades\App;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\v1\Auth\LoginRequest;
use App\Http\Requests\Api\v1\Auth\ConfirmRequest;

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
            $request->validate([
                'phone' => 'required|unique:users,phone|max:55',
                'password' => 'required'
            ]);
            $phone = $request->input('phone');
            $password = $request->input('password');
       
            $user = User::where('phone', '=', $phone)->first();
            if (!$user) {
               return response()->json(['success'=>false, 'message' => 'Login Fail, please check phone number']);
            }
            if (!Hash::check($password, $user->password)) {
               return response()->json(['success'=>false, 'message' => 'Login Fail, pls check password']);
            }
            $otp = rand(100000,999999);
            $user->verify_token = $otp;
            $user->save();
            //    return response()->json(['success'=>true,'message'=>'success', 'data' => $user]);
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

    public function handleErrors()
    {
        return response()->json(['status' => false], Response::HTTP_UNAUTHORIZED);
    }
}

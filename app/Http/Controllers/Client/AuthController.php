<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use App\Mail\sendEmail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\DatabaseConnection;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Propaganistas\LaravelPhone\PhoneNumber;
use App\Http\Requests\Api\v1\Auth\LoginRequest;
use App\Http\Requests\Api\v1\Auth\ConfirmRequest;
use App\Services\OtpProviderHandler;

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
                'phone' => 'required|phone:AUTO',
                'password' => 'required',
                'languagemother_id' => 'required'
            ]);

            $validatedData['password'] = bcrypt($request->password);

            $user = User::create($validatedData);
            $otp = rand(100000, 999999);
            $user->verify_token = $otp;
            $user->save();

            OtpProviderHandler::sendOtp($user->phone, $otp);

            return response()->json(['success' => true, 'message' => 'success']);

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
                'phone' => 'required|phone:AUTO',
                'password' => 'required'
            ]);
            $phone = PhoneNumber::make($request->input('phone'));
            $password = $request->input('password');

            $user = User::where('phone', '=', $request->input('phone'))->first();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Login Fail, please check phone number']);
            }
            if (!Hash::check($password, $user->password)) {
                return response()->json(['success' => false, 'message' => 'Login Fail, pls check password']);
            }
            $otp = rand(100000, 999999);
            $user->verify_token = $otp;
            $user->save();

            OtpProviderHandler::sendOtp($phone, $otp);

            return response()->json(['success' => true, 'message' => 'success']);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'errors' => [$th->getMessage()]
            ], 500);
        }
    }
    public function confirm(Request $request)
    {
        try {
            $request->validate([
                'phone' => 'required|phone:AUTO',
                'otp' => 'required',
            ]);
            $user = User::where('phone', '=', $request->input('phone'))->first();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Login Fail, please check phone number']);
            }
            if ($user->verify_token != $request->otp) {
                return response()->json(['success' => false, 'message' => 'Login Fail, pls check otp']);
            }
            Auth::login($user);
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

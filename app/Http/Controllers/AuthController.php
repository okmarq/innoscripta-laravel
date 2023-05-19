<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request)
    {
        $request->merge(['password' => Hash::make($request->password)]);
        $user = User::create($request->all());
        $token = $user->createToken('Registration')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(UpdateUserRequest $request)
    {
        if (
            Auth::attempt([
                'email' => $request->email,
                'password' => $request->password,
            ])
        ) {
            $user = User::find(auth()->id());
            $token = $user->createToken('Login')->plainTextToken;

            return response([
                'user' => $user,
                'token' => $token
            ], 200);
        }

        throw new AuthenticationException(
            'Your credentials does not match our record.'
        );
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response([
            'message' => 'Logged out'
        ], 200);
    }
}

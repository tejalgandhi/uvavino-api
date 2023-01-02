<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WineAuthController extends Controller
{

    public function register(UserRegistrationRequest $request) {
        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);
        return response()->json($this->login($request), 201);
    }

    public function login(Request $request) {
        $user = User::whereEmail($request->email)->first();
        if (!$user) {
            return "User not found";
        }
        if(!Hash::check($request->password, $user->password)) {
            return "Invalid Password !";
        }
        $token = $user->createToken('login');
        return ['token' => $token->plainTextToken];
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return "done";
    }
}

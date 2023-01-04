<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegistrationRequest;
use App\Http\Resources\AuthToken;
use App\Http\Resources\Fail;
use App\Http\Resources\Success;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WineAuthController extends Controller
{

    public function register(UserRegistrationRequest $request) {
        $user_attrs = [
            ...$request->only(['username', 'first_name', 'last_name', 'email']),
            "password" => Hash::make($request->password)
        ];
        // dd($user_attrs);
        User::create($user_attrs);
        return $this->_login($request, 201);
    }

    public function login(Request $request) {
        return $this->_login($request);
    }

    protected function _login(Request $request, $status = 200) {
        $user = User::whereEmail($request->email)->first();
        if ( !$user ) {
            return (new Fail("User not found"))->json();
        }
        if ( !Hash::check($request->password, $user->password) ) {
            return (new Fail("Invalid Password !"))->json();
        }
        $token = $user->createToken('login');
        return (new AuthToken($token->plainTextToken))->json($status);
    }

    public function logout(Request $request) {
        $user = $request->user();
        if (!$user) return (new Fail('User/token Invalid'))->json(400);
        $user->tokens()->delete();
        return (new Success("Logged out."))->json();
    }
}

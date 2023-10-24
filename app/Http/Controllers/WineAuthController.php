<?php

namespace App\Http\Controllers;

use App\Events\ForgotPassword;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Http\Requests\WineUserRequest;
use App\Http\Resources\AuthToken;
use App\Http\Resources\Fail;
use App\Http\Resources\Success;
use App\Http\Resources\WineUserResource;
use App\Models\WineUser;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class WineAuthController extends Controller
{

    public function register(UserRegistrationRequest $request)
    {
        $user_attrs = [
            ...$request->only(['username', 'first_name', 'last_name', 'email']),
            "password" => Hash::make($request->password)
        ];
        WineUser::create($user_attrs);
        return $this->_login($request, 201);
    }

    protected function _login(Request $request, $status = 200)
    {
        $user = WineUser::whereEmail($request->email)->first();
        if (!$user) {
            return (new Fail(__('auth.not_found')))->json(404);
        }
        if (!Hash::check($request->password, $user->password)) {
            return (new Fail(__('auth.incorrect_password')))->json();
        }

        $token = $user->createToken('login')->plainTextToken;
        return response()->json([
            'status' => $status,
            'user' => new WineUserResource($user),
            'token' => $token,
        ]);
    }

    public function login(Request $request)
    {
        return $this->_login($request);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if (!$user) return (new Fail(__('auth.invalid_user')))->json(400);
        $user->tokens()->delete();
        return (new Success(__('auth.logout')))->json();
    }

    public function forget(ForgotPasswordRequest $request)
    {
        $user = WineUser::whereEmail($request->email)->first();
        if (!$user) return (new Fail(__('auth.not_found')))->json(404);
        $token = Password::createToken($user);
        event(new ForgotPassword($user, $token));
        return (new Success(__('passwords.sent')))->json();
    }

    public function reset(Request $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ]);
                $user->save();
            }
        );

        return (match($status){
        Password::PASSWORD_RESET => new Success(__($status)),
            default => new Fail(__($status))
        })->json();

    }

    public function getUser(Request $request)
    {
        $user = Auth::user();
        return response()->json([
            'status' => 200,
            'user' => new WineUserResource($user),
        ]);
    }

    public function updateUser(WineUserRequest $request)
    {
        try{
            $userArr = [
                'first_name'=> $request->first_name,
                'last_name'=> $request->last_name,
                'city'=> $request->city,
                'country'=> $request->country,
            ];
            $user= WineUser::whereId(Auth::user()->id)->first();
            $user->fill($userArr)->save();

            if ($request->hasFile('image')) {
                $user->uploadImages('image', $request->image, ['user']);
            }
            return response()->json([
                'status' => 200,
                'user' => new WineUserResource($user),
            ]);
        } catch (\Exception $e) {
            return (new Fail($e->getMessage()))->json();
        }
    }
}

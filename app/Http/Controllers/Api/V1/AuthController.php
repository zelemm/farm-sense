<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use App\Services\V1\UserService;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService;
    }

    public function login(LoginRequest $request)
    {

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $token = $user->createToken('Farm Sense')->accessToken;
            return response()->json([
                'status' => 200,
                'data' => [
                    'token' => $token
                ]
            ]);
        }

        return response()->json([
            'status' => 401,
            'message' => __('common.auth.login_error')
        ], 401);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'success' => true,
            'message' => __('common.auth.logged_out')
        ]);
    }

     /**
     * Forgot password
     */
    public function resetPassword(ForgotPasswordRequest $request)
    {
        $status = $this->userService->sendForgotPassword($request->email);

        return response()->json([
            'success' => $status === Password::RESET_LINK_SENT ? true : false,
            'message' => __($status),
        ]);
    }

    /**
     * Change password
     */
    public function changePassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return response()->json([
            'success' => $status === Password::PASSWORD_RESET ? true : false,
            'message' => __($status),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ChangePasswordRequest;
use App\Http\Requests\Users\UpdateProfileRequest;
use App\Http\Resources\UserProfileResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{

    public function __construct()
    {
    }

    public function changePassword(ChangePasswordRequest $request, User $user)
    {
//        $this->authorize('change-password', $user);

        if (Hash::check($request->old_password, $user->password)) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'success' => true,
                'message' => __('form.message.password_has_been_successfully_changed')
            ]);
        }

        return response()->json([
            'success' => false,
            'errors' => [
                'old_password' => [
                    __('form.message.the_old_password_is_invalid')
                ]
            ]
        ], 403);
    }

    /**
     * Get user profile
     * @return Response
     */
    public function profile()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => new UserProfileResource(Auth::user()),
            ]
        ]);
    }

    /**
     * Update user profile
     * @return Response
     */
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $params = $request->only([
            'name'
        ]);

        $user->update($params);

        return response()->json([
            'success' => true,
            'message' => __('form.user.updated'),
            'data' => [
                'user' => new UserProfileResource($user),
            ]
        ]);
    }

}

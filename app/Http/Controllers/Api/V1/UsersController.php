<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ChangePasswordRequest;
use App\Http\Requests\Users\UpdateProfileRequest;
use App\Http\Resources\UserProfileResource;
use App\Http\Resources\V1\UserListResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    protected UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService;
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

    /**
     * remove a photo for user
     * @param int $useId
     * @return Response
     */
    public function destroyPhoto($useId)
    {
        $user = User::withTrashed()->findOrFail($useId);
        $this->authorize('destroy-photo', $user);

        $status = $user->deleteProfilePhoto();

        return response()->json([
            'success' => $status
        ]);
    }

    public function getCertificates()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => [
                'certificates' => CertificatesResource::collection($user->certificates),
            ],
        ]);
    }

    public function userPing()
    {
        Auth::user()->update([
            'is_online' => true,
            'last_login_at' => now(),
        ]);

        return response()->json([]);
    }

    public function list()
    {
        Gate::authorize('material-users-list', User::class);
        $roles = is_string(request()->roles) ? explode(',', request()->roles) : [];
        $users = (new \App\Services\V1\UserService())->getUsersByRole($roles);

        return response()->json([
            'success' => true,
            'data' => [
                'datas' => UserListResource::collection($users),
            ],
        ]);
    }

    public function switchRole()
    {
        $role = request()->role;

        $user = Auth::user();

        $role = Role::where('id', '!=', $user->role_id)
            ->firstWhere('role', $role);

        if ($role) {
            $user->update([
                'role_id' => $role->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => __('form.user.switch_success'),
            'data' => [
                'user' => new UserProfileResource($user),
            ]
        ]);
    }

    public function syncLocations(User $user)
    {
        $action = request()->action;
        $locationIds = request()->location_ids;

        if (is_array($locationIds) && count($locationIds)) {
            if ($action == 'attach') {
                $user->locations()->attach($locationIds);
            } else {
                $user->locations()->detach($locationIds);
            }
        }

        return response()->json([
            'success' => true,
            'message' => __('form.user.locations_synced')
        ]);
    }
}

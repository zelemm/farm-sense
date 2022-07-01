<?php
namespace App\Services;

use App\Enums\UserGender;
use App\Enums\UserType;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserService
{

    /**
     * Create user profile
     * @param Array $data
     * @return User $user
     */
    public function create($data)
    {
        $role = Role::where('role', $data['role'])->first();

        $birthDate = data_get($data, 'birth_date') ?: data_get($data, 'dob');

        $subscribe = true;
        $user_auth = Auth::user()->id;

        if (isset($data['subscribe'])) {
            $subscribe = $data['subscribe'] ? true : false;
        }

        $user = User::create([
            'name' => data_get($data, 'name'),
            'email' => data_get($data, 'email'),
            'password' => bcrypt(data_get($data, 'password')),
            'account_id' => data_get($data, 'account_id', 1),
            'user_type' => data_get($data, 'user_type', UserType::CUSTOMER),
            'role_id' => data_get($role, 'id', 2),
            'gender' => data_get($data, 'gender', UserGender::FEMALE),
            'birth_date' => $birthDate ? Carbon::parse($birthDate)->format('Y-m-d 00:00:00') : null,
            'lang' => data_get($data, 'lang', 'pt'),
            'subscribe' => $subscribe,
            'added_by' => $user_auth,
            'updated_by' => $user_auth
        ]);

        $user->roles()->sync(data_get($role, 'id', 2));

        return $user;
    }

    /**
     * Customer update user profile
     * @param User $user
     * @param array $data
     * @return User $user
     */
    public function updateProfile(User $user, Array $data)
    {
        $birthDate = data_get($data, 'birth_date') ?: data_get($data, 'dob');

        $subscribe = true;

        if (isset($data['subscribe'])) {
            $subscribe = $data['subscribe'] ? true : false;
        }

        $user->update([
            'name' => data_get($data, 'name', $user->name),
            'lang' => data_get($data, 'lang', $user->lang),
            'gender' => data_get($data, 'gender', $user->gender),
            'birth_date' => $birthDate ? Carbon::parse($birthDate)->format('Y-m-d 00:00:00') : null,
            'subscribe' => $subscribe,
            'updated_by' => Auth::user()->id
        ]);

        $photo = data_get($data, 'photo_path');

        if ($photo) {
            $user->updateProfilePhoto($photo);
        }

        return $user;
    }
}

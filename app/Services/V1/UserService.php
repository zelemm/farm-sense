<?php

namespace App\Services\V1;

use App\Enums\EmployeeType;
use App\Enums\UserGender;
use App\Enums\UserRole;
use App\Enums\UserType;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class UserService
{

    protected $performed_by = 0;

    /**
     * @param $performed_by
     * @return void
     */
    public function setPerformedBy($performed_by){
        $this->performed_by = $performed_by;
    }

    /**
     * @return int
     */
    public function getPerformedBy(){
        if($this->performed_by && $this->performed_by > 0){
            return $this->performed_by;
        }
        $auth_user = Auth::user();
        if($auth_user) return $auth_user->id;
        return 0;
    }

    /**
     * Get all users
     * @param Array $queries
     * @return Array $users
     */
    public function all($queries)
    {
        Paginator::currentPageResolver(function() use ($queries) {
            return data_get($queries, 'page', 1);
        });

        $orderBy = data_get($queries, 'sortBy');
        $orderDir = data_get($queries, 'sortDesc');

        if (array_search($orderDir, ['desc', 'asc']) === false) {
            $orderDir = 'desc';
        }

        $sortColumns = [
            'name', 'email', 'lang', 'user_type', 'gender'
        ];

        if (!in_array($orderBy, $sortColumns)) {
            $orderBy = 'name';
        }

        $filters = [
            'search' => data_get($queries, 'search', null),
            'trashed' => data_get($queries, 'trashed', null),
        ];

        $roles = is_string(request()->roles) ? explode(',', request()->roles) : UserRole::ALL;
        $userRoles = Role::whereIn('role', $roles)
            ->pluck('id')
            ->toArray();

        $locationId = data_get($queries, 'location_id');
        $auth_user = Auth::user();
        if(!$auth_user->is_super_admin) {
            $user_location_ids = (new UserService())->getUserLocations($auth_user);
            $locationId = array_unique(array_merge($locationId ?? [], $user_location_ids));
        }
        $users = User::select('users.*')
            ->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->when($locationId, function ($query, $locationId) {
                $query->leftJoin('location_user', 'location_user.user_id', '=', 'users.id')
                    ->where('location_user.location_id', $locationId);
            });
        $users = $users->whereIn('role_user.role_id', $userRoles)
            ->groupBy('users.id')
            ->filter($filters)
            ->orderBy($orderBy, $orderDir)
            ->groupBy('users.id');

        // pivot search associatedLabs
        $pivotFields = [
            'associated_lab_id' => is_string(request()->associated_labs) ? explode(',', request()->associated_labs) : [],
        ];

        if (head($pivotFields)) {
            $users = $users->select('users.*')
                ->with('associatedLabs')
                ->join('associated_lab_user', 'associated_lab_user.user_id', '=', 'users.id');
            foreach($pivotFields as $pCol => $pFieldId) {
                if ($pFieldId && $pFieldId != 'null') {
                    $users = $users->whereIn('associated_lab_user.' . $pCol, $pFieldId);
                }
            }
        }

        $users = $users->paginate(intval(data_get($queries, 'itemsPerPage', 10)));

        $roles = Role::whereIn('role', [UserType::ADMIN, UserType::SUPER_ADMIN, UserType::INVENTORY])->select('role')->get();

        return [
            'data' => UserResource::collection($users),
            'meta' => [
                'total' => $users->total(),
                'roles' => $roles,
            ]
        ];
    }

    /**
     * Create user
     * @param Array $data
     * @return User $user
     */
    public function create($data)
    {
        // role
        $role = Role::firstWhere('role', data_get($data, 'role'));
        $roles = is_array(data_get($data, 'roles')) ? data_get($data, 'roles') : [];

        // default role = first roles if not have
        if (!$role || !in_array($role->id, $roles)) {
            $role = Role::find(data_get($roles, '0', 2));
        }

        if (count($roles)) {
            $data['user_type'] = $role->role;

            if (in_array($role->role, EmployeeType::EMPLOYEE)) {
                $data['user_type'] = UserType::EMPLOYEE;
            }
        } else {
            $roles = [$role->id];
        }

        $birthDate = data_get($data, 'birth_date') ?: data_get($data, 'dob');

        //Get User Group id
        $authUser = $this->getPerformedBy();
        if($authUser > 0){
            $authUser = User::find($authUser);
        }

        $subscribe = true;

        if (isset($data['subscribe'])) {
            $subscribe = $data['subscribe'] ? true : false;
        }
        $auth_user = $authUser->id;
        $user = User::create([
            'name' => data_get($data, 'name'),
            'email' => data_get($data, 'email'),
            'password' => bcrypt(data_get($data, 'password')),
            'account_id' => data_get($data, 'account_id'),
            'user_type' => data_get($data, 'user_type'),
            'role_id' => data_get($role, 'id', 2),
            'gender' => data_get($data, 'gender'),
            'birth_date' => $birthDate ? Carbon::parse($birthDate)->format('Y-m-d 00:00:00') : null,
            'lang' => data_get($data, 'lang', 'pt'),
            'owner' => data_get($data, 'owner', false),
            'subscribe' => $subscribe,
            'added_by' => $auth_user,
            'updated_by' => $auth_user,
        ]);

        // sync roles
        $user->roles()->sync($roles);

        // update requesters, associatedLabs for admin
        $isAdmin = in_array(UserRole::ADMIN, $user->roles->pluck('role')->toArray());
        if ($isAdmin) {
            $requestersData = data_get($data, 'requesters');
            $this->updateUserData($user, $requestersData, 'requesters');

            $associatedLabsData = data_get($data, 'associated_labs');
            $this->updateUserData($user, $associatedLabsData, 'associated_labs');
        }

        // sync locations
        $locationsData = data_get($data, 'locations');
        $this->updateUserData($user, $locationsData);

        // update user groups
        if($user->role_id != 2){
            $userGroupIds = $this->getUserGroupIds($authUser);
            $this->updateUserData($user, $userGroupIds, 'user_groups');
        }

        $photo = data_get($data, 'photo_path');

        if ($photo) {
            $user->updateProfilePhoto($photo);
        }

        // check if have employee role
        $employeeRoles = Role::whereIn('role', EmployeeType::EMPLOYEE)
            ->get()
            ->pluck('id')
            ->toArray();

        // create employee
        if(!$user->employee && (array_intersect($roles, $employeeRoles) || isset($data['is_this_employee']))) {
            $user->employee()->create([
                'account_id' => $user->account_id,
                'email' => $user->email,
                'name' => $user->name,
                'emp_type' => $user->user_type,
            ]);
        }

        return $user;
    }

    /**
     * Update user data
     * @param User $user
     * @param Array $data
     * @return User $user
     */
    public function update(User $user, $data)
    {
        // role
        $role = Role::firstWhere('role', data_get($data, 'role', data_get($user->role, 'role')));

        // sync roles
        $roles = is_array(data_get($data, 'roles')) ? data_get($data, 'roles') : [];

        // default role = first roles if not have
        if (!$role || !in_array($role->id, $roles)) {
            $role = Role::find(data_get($roles, '0', 2));
        }

        if (count($roles)) {
            $user->roles()->sync($roles);

            $data['user_type'] = $role->role;

            if (in_array($role->role, EmployeeType::EMPLOYEE)) {
                $data['user_type'] = UserType::EMPLOYEE;
            }
        }

        $birthDate = data_get($data, 'birth_date') ?: data_get($data, 'dob');

        //Get User Group id
        $authUser = Auth::user();

        $user->update([
            'name' => data_get($data, 'name', $user->name),
            'gender' => data_get($data, 'gender', UserGender::FEMALE),
            'birth_date' => $birthDate ? Carbon::parse($birthDate)->format('Y-m-d 00:00:00') : $user->birth_date,
            'owner' => data_get($data, 'owner', false),
            'lang' => data_get($data, 'lang', 'pt'),
            'user_type' => data_get($data, 'user_type', $user->user_type),
            'role_id' => data_get($role, 'id', 2),
            'subscribe' => data_get($data, 'subscribe', $user->subscribe) ? true : false,
            'updated_by' => $authUser->id,
        ]);

        $isAdmin = in_array(UserRole::ADMIN, $user->roles->pluck('role')->toArray());

        // update requesters
        if (array_key_exists('requesters', $data)) {
            $requestersData = $isAdmin ? data_get($data, 'requesters') : '';
            $this->updateUserData($user, $requestersData, 'requesters');
        }

        // update locations
        if (array_key_exists('locations', $data)) {
            $locationsData = data_get($data, 'locations');

            $this->updateUserData($user, $locationsData);
        }

        // update associatedLabs
        if (array_key_exists('associated_labs', $data)) {
            $associatedLabsData = $isAdmin ? data_get($data, 'associated_labs') : '';
            $this->updateUserData($user, $associatedLabsData, 'associated_labs');
        }

        // update user groups
        if (array_key_exists('user_groups', $data)) {
            $userGroupIds = $this->getUserGroupIds($authUser);
            $this->updateUserData($user, $userGroupIds, 'user_groups');
        }

        $password = data_get($data, 'password');

        if ($password) {
            $user->update([
                'password' => bcrypt($password)
            ]);
        }

        $photo = data_get($data, 'photo_path');

        if ($photo) {
            $user->updateProfilePhoto($photo);
        }

        // check if have employee role
        $employeeRoles = Role::whereIn('role', EmployeeType::EMPLOYEE)
            ->get()
            ->pluck('id')
            ->toArray();

        // create employee
        if(!$user->employee && (array_intersect($roles, $employeeRoles) || isset($data['is_this_employee']))) {
            $user->employee()->create([
                'account_id' => $user->account_id,
                'email' => $user->email,
                'name' => $user->name,
                'emp_type' => $user->user_type,
            ]);
        }

        return $user;
    }

    /**
     * Update user locations
     * @param User $user
     * @param String $data
     */
    public function updateUserData(User $user, $data = '', $type = 'locations')
    {
        $dataIds = is_array($data) ? $data : explode(',', $data);

        $dataIds = array_filter(array_map(function ($id) {
            return  preg_replace('/[^0-9]/', '', $id);
        }, $dataIds));

        switch ($type) {
            case 'locations':
                $user->locations()->sync($dataIds);
                break;
            case 'requesters':
                $user->requesters()->sync($dataIds);
                break;
            case 'associated_labs':
                $user->associatedLabs()->sync($dataIds);
                break;
            case 'user_groups':
                $user->userGroups()->sync($dataIds);
                break;
        }
    }

    /**
     * Send email reset password
     * @return String $status
     */
    public function sendForgotPassword($email)
    {
        $status = Password::sendResetLink(['email' => $email]);

        return $status;
    }

    public function getUsersByRole($roles = [], $withEmployee = 0, $excludingRoles = []){
        $user = Auth::user();

        $roleIds = Role::query();
        if(is_array($excludingRoles) && count($excludingRoles) > 0){
            $roleIds = $roleIds->whereNotIn('role', $excludingRoles);
        }

        if(is_array($roles) && count($roles) > 0){
            $roleIds = $roleIds->whereIn('role', $roles);
        }
        $roleIds = $roleIds->pluck('id')->toArray();

        $users = User::select('users.*');
        if($withEmployee == 1){
            $users = $users->with('employee');
        }
        $users = $users->leftJoin('role_user', 'role_user.user_id', '=', 'users.id')
            ->whereIn('role_user.role_id', $roleIds)
            ->groupBy('users.id');

        if ($user && !$user->is_super_admin) {
            $locationsIds = $this->getUserLocations($user);
            $users = $users->where(function ($query) use($locationsIds, $user) {
                return $query->where('users.added_by', $user->id)
                    ->orWhere('users.id', $user->id)
                    ->orWhere('users.id', 'in', DB::raw("SELECT user_id FROM location_user where location_id in (".implode(",", $locationsIds).")"));
            });
        }

        $users = $users->orderBy('name', 'ASC')->get();

        return $users;
    }

    public function getUserLocations($user){
        return $user ? $user->locations()->allRelatedIds()->toArray() : [];
    }

    public function getUserGroupIds($authUser)
    {
        $userGroupIds = [];

        if ($authUser) {
            if($authUser->is_customer) {
            } else if($authUser->is_super_admin) {
                $userGroupIds = request()->get('user_groups');
            } else {
                $userGroupIds = $authUser->userGroups()->allRelatedIds();
            }
        }

        return $userGroupIds;
    }
}

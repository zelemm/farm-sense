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

    /**
     * Send email reset password
     * @return String $status
     */
    public function sendForgotPassword($email)
    {
        $status = Password::sendResetLink(['email' => $email]);

        return $status;
    }

}

<?php

namespace App\Models;

use App\Enums\UserType;
use App\Notifications\PasswordReset;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as ICanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Model implements AuthenticatableContract, AuthorizableContract, ICanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword, Authenticatable, Authorizable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'lang','user_type','role','added_by','updated_by','deleted_by',
        'email', 'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function addedBy(){
        return User::withTrashed()->find($this->added_by);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }

    public function getIsFarmHandsAttribute()
    {
        return $this->user_type == UserType::FARM_HANDS;
    }

    public function getIsAdminAttribute()
    {
        return $this->user_type == UserType::ADMIN;
    }

    public function getIsSuperAdminAttribute()
    {
        return $this->user_type == UserType::SUPER_ADMIN;
    }

    public function getIsGuestsAttribute()
    {
        return $this->user_type == UserType::GUESTS;
    }

    public function getLangAttribute($value)
    {
        return $value ?: 'en';
    }

    public function farm(){
        return $this->hasOne(Farm::class, 'id', 'farm_id');
    }
}

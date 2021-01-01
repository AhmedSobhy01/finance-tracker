<?php

namespace App\Models;

use App\Traits\TimezonesTrait;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use TimezonesTrait;

    protected $fillable = ['username', 'timezone', 'password'];
    protected $hidden = ['password', 'remember_token'];
}
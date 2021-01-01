<?php

namespace App\Models;

use Carbon\Carbon;
use App\Traits\TimezonesTrait;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    use TimezonesTrait;

    public $timestamps = false;
    protected $fillable = ['user_id', 'login_ip', 'login_at'];
    protected $dates = ['login_at'];

    public function setLogInLog(){
        $this->insert([
            'user_id' => auth()->id(),
            'login_ip'=> request()->getClientIp(),
            'login_at' => Carbon::now(),
        ]);
    }
}
<?php

namespace App\Models;

use App\Traits\TimezonesTrait;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use TimezonesTrait;

    protected $fillable = ['name'];

    public function dues()
    {
        return $this->hasMany('App\Models\Due');
    }
}
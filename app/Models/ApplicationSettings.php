<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationSettings extends Model
{
    public $timestamps = false;
    protected $fillable = ['key', 'value'];
}
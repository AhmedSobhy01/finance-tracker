<?php

namespace App\Models;

use App\Traits\TimezonesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cash extends Model
{
    use SoftDeletes;
    use TimezonesTrait;

    protected $fillable = ['process_serial', 'amount', 'serial_number', 'description'];

    public function getAmountAttribute($value)
    {
        return number_format($value, 2) . ' ' . applicationSettings('currency');
    }

    public function getDescriptionAttribute($value)
    {
        return $value ?? "-";
    }

    public function getDeletedAtAttribute($value)
    {
        return $value ? convertToUserTimezone($value) :  $value;
    }

    public function scopeTotal()
    {
        return $this->sum('amount');
    }
}

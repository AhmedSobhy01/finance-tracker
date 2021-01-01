<?php

namespace App\Models;

use App\Traits\TimezonesTrait;
use Illuminate\Database\Eloquent\Model;

class Due extends Model
{
    use TimezonesTrait;

    protected $fillable = ['process_serial', 'type', 'amount', 'description', 'person_id', 'paid_at', 'created_at'];
    protected $dates = ['paid_at'];

    public function person()
    {
        return $this->belongsTo('App\Models\Person');
    }

    public function getTypeAttribute($value)
    {
        return $value == 0 ? 'Borrowed' : 'Lended';
    }

    public function getAmountAttribute($value)
    {
        return number_format($value, 2) . ' ' . applicationSettings('currency');
    }

    public function getDescriptionAttribute($value)
    {
        return $value ?? "-";
    }

    public function getPaidAtAttribute($value)
    {
        return $value ? convertToUserTimezone($value) :  $value;
    }

    public function scopeLendings($query)
    {
        return $query->where('type', 0);
    }

    public function scopeBorrowings($query)
    {
        return $query->where('type', 1);
    }
}
<?php

namespace App\Traits;

trait TimezonesTrait
{
    public function getCreatedAtAttribute($value)
    {
        return convertToUserTimezone($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return convertToUserTimezone($value);
    }
}
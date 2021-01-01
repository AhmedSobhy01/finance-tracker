<?php

use App\Models\ApplicationSettings;
use Carbon\Carbon;
use App\Models\Due;
use App\Models\Cash;
use App\Models\Transaction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

if (!function_exists('pageActive')) {
    function pageActive($page)
    {
        return Route::currentRouteName() == $page ? true : false;
    }
}

if (!function_exists('shorten_number')) {
    /**
     * Return a simplified version of a number.
     *
     * @param  int  $number
     * @return string
     *
     */
    function shorten_number(int $number)
    {
        $suffix = ["", "K", "M", "B"];
        $precision = 2;
        for ($i = 0; $i < count($suffix); $i++) {
            $divide = $number / pow(1000, $i);
            if ($divide < 1000) {
                return round($divide, $precision) . $suffix[$i];
                break;
            }
        }
    }
}

if (!function_exists('generateProcessSerial')) {
    /**
     * Return a randomly generated unique string.
     *
     * @return string
     *
     */
    function generateProcessSerial()
    {
        while (True) {
            $serial = strtoupper(Str::random(15));

            $transaction = Transaction::where('process_serial', $serial)->first();
            $due = Due::where('process_serial', $serial)->first();
            $cash = Cash::where('process_serial', $serial)->first();

            if (!$transaction && !$due && !$cash) break;
        }

        return $serial;
    }
}

if (!function_exists('convertToUserTimezone')) {
    /**
     * Return a datetime with the user's timezone.
     *
     * @return string
     *
     */
    function convertToUserTimezone(string $datetime)
    {
        $timezone = auth()->user()->timezone ?? config('app.timezone');

        return Carbon::parse($datetime)->timezone($timezone);
    }
}

if (!function_exists('applicationSettings')) {
    /**
     * Get application setting.
     *
     * @return string
     *
     */
    function applicationSettings(string $key)
    {
        return Cache::get('application_settings')[$key];
    }
}

if (!function_exists('setApplicationSettings')) {
    /**
     * Set application setting.
     *
     * @return string
     *
     */
    function setApplicationSettings(string $key, string $value)
    {
        ApplicationSettings::where('key', $key)->update(['value' => $value]);
    }
}

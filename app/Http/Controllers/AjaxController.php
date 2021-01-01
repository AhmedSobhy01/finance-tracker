<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\LoginLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AjaxController extends Controller
{
    public function getLoginLog(Request $request)
    {
        if (!$request->wantsJson()) return abort(404);

        try {
            $logs = LoginLog::paginate(5);

            $last_page = !$logs->hasMorePages();

            $logs = [
                'logs' => $logs->map(function ($v){
                    return [
                        'user_id' => $v->user_id,
                        'user_username' => User::find($v->user_id)->username,
                        'user_url' => route('accounts.edit', $v->user_id),
                        'login_time' => $v->login_at->format('Y-m-d h:i:s A'),
                        'login_ip' => $v->login_ip,
                    ];
                }),
                'end' => $last_page
            ];

            return response_ok('', '', $logs);
        } catch (\Exception $e){
            return response_server_error();
        }
    }

    public function setStartDate(Request $request)
    {
        if (!$request->wantsJson()) return abort(404);

        try {
            $date = Carbon::now();
            setApplicationSettings('start_date', $date);

            return response_created('', '', ['startDate' => $date->format('Y-m-d H:i:s')]);
        } catch (\Exception $e){
            return response_server_error();
        }
    }

    public function clearCache(Request $request)
    {
        if (!$request->wantsJson()) return abort(404);

        try {
            Artisan::call('cache:clear');

            return response_created('', '');
        } catch (\Exception $e){
            return response_server_error();
        }
    }
}
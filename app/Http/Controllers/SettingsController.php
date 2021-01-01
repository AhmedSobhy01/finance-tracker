<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AccountRequest;

class SettingsController extends Controller
{
    public function edit()
    {
        $timezones = config('timezones');
        return view('settings', compact('timezones'));
    }

    public function update(AccountRequest $request)
    {
        try{
            auth()->user()->update([
                'username' => $request->get('username'),
                'timezone' => $request->get('timezone'),
            ]);

            if ($request->get('password')) {
                auth()->user()->update([
                    'password' => Hash::make($request->get('password')),
                ]);
            }

            return redirect()->route('settings.edit')->with('success', __('messages.body.account_settings_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->route('settings.edit')->with('error', __('messages.body.error'));
        }
    }
}
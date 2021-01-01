<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApplicationSettingsController extends Controller
{
    public function index()
    {
        return view('application_settings');
    }

    public function update(Request $request)
    {
        $request->validate([
            'currency' => 'required|string|max:25',
            'pagination_count' => 'required|integer|min:5|max:200',
            'owner' => 'required|string|max:50',
        ]);

        try {
            setApplicationSettings('currency', $request->get('currency'));
            setApplicationSettings('pagination_count', $request->get('pagination_count'));
            setApplicationSettings('owner', $request->get('owner'));

            return redirect()->route('application.settings.update')->with('success', __('messages.body.applications_settings_updated_successfully'));
        } catch (\Exception $e){
            return redirect()->route('application.settings.edit')->with('error', __('messages.body.error'));
        }
    }
}

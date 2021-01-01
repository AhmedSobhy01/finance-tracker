<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AccountRequest;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = User::where('id', '!=', auth()->id())->latest()->paginate(applicationSettings('pagination_count'));

        return view('accounts.index', compact('accounts'));
    }

    public function store(Request $request)
    {
        if (!$request->wantsJson()) return abort(404);

        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|max:255|unique:users,username',
                'password' => 'required|string|confirmed|max:255',
                'timezone' => 'nullable|timezone',
            ], [
                'username.required' => __('custom_validation.username.required'),
                'username.string' => __('custom_validation.username.invalid'),
                'username.max' => __('custom_validation.username.max:255'),
                'username.unique' => __('custom_validation.username.unique'),
                'password.required' => __('custom_validation.password.required'),
                'password.string' => __('custom_validation.password.invalid'),
                'password.confirmed' => __('custom_validation.password.confirmed'),
                'password.max' => __('custom_validation.password.max:255'),
                'timezone.string' => __('custom_validation.timezone.invalid'),
            ]);
            if ($validator->fails()) return response_invalid_request(null, null, $validator->errors()->toArray());

            $account = User::create([
                'username' => $request->get('username'),
                'timezone' => $request->get('timezone') ?? 'UTC',
                'password' => Hash::make($request->get('password')),
            ]);

            $data = $account->only(['id', 'username', 'created_at']);
            $data['created_at'] = $data['created_at']->format('Y-m-d h:i:s A');
            $data['edit_url'] = route('accounts.edit', $data['id']);

            return response_created("", "", $data);
        } catch (\Exception $e){
            return response_server_error();
        }
    }

    public function edit(User $account)
    {
        $account_permissions = $account->permissions()->get()->pluck('name')->toArray();
        $permissions = config('permission.permissions');

        $timezones = config('timezones');

        return view('accounts.edit', compact('account', 'timezones', 'account_permissions', 'permissions'));
    }

    public function update(AccountRequest $request, User $account)
    {
        try {
            $account->update([
                'username' => $request->get('username'),
                'timezone' => $request->get('timezone')
            ]);

            if ($request->get('password')) {
                $account->update([
                    'password' => Hash::make($request->get('password')),
                ]);
            }

            $permissions = $request->get('permissions') ?? [];
            $account->syncPermissions($permissions);

            return redirect()->route('accounts.edit', $account->id)->with('success', __('messages.body.account_updated_successfully'));
        } catch (\Exception $e) {
            return redirect()->route('accounts.edit', $account->id)->with('error', __('messages.body.error'));
        }
    }

    public function destroy(Request $request)
    {
        if (!$request->wantsJson()) return abort(404);

        try {
            $validator = Validator::make($request->all(), [
                'accountId' => 'required|integer',
            ], [
                'accountId.required' => __('custom_validation.accountId.required'),
                'accountId.integer' => __('custom_validation.accountId.invalid'),
            ]);
            if ($validator->fails()) return response_invalid_request(null, null, $validator->errors()->toArray());

            $account = User::find($request->get('accountId'));
            if (!$account) return response_not_found(__('messages.title.not_found'), __('messages.body.not_found'));

            $account->delete();
            return response_ok(__('messages.title.deleted'), __('messages.body.account_deleted_successfully'));
        } catch (\Exception $e){
            return response_server_error();
        }
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'timezone' => 'nullable|timezone',
            'password' => 'nullable|string|confirmed|max:255',
        ];

        if (Route::currentRouteName() == 'accounts.update') {
            $rules += [
                'username' => 'required|string|max:255|unique:users,username,' . $this->account->id,
                'permissions' => 'nullable|exists:permissions,name'
            ];
        }
        if (Route::currentRouteName() == 'settings.update') {
            $rules += [
                'username' => 'required|string|max:255|unique:users,username,' . auth()->id()
            ];
        }

        return $rules;
    }

}
@extends('layouts.app')
@section('title', __('main.edit_account'))

@section('content')
<div class="row">
    <div class="offset-lg-2 col-lg-8">
        <div class="card">
            <div class="card-header text-center"><h4 class="m-0"><i class="fas fa-cogs mr-2"></i> {{ __('main.edit_account') }}</h4></div>
            <div class="card-body">
                <form action="{{ route('accounts.update', $account->id) }}" method="POST">
                    @csrf
                    @method('patch')
                    <div class="form-body">
                        @include('partials._success')
                        @include('partials._error')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="username">{{ __('main.username') }}:</label>
                                    <input
                                        type="text"
                                        id="username"
                                        class="form-control"
                                        placeholder="{{ __('main.username') }}"
                                        name="username"
                                        value="{{ $account->username }}"
                                        autocomplete="off"
                                    />
                                    @error('username')
                                        <span class="invalid-feedback d-block mt-2 ml-2" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="timezone">{{ __('main.timezone') }}:</label>
                                    <select
                                        id="timezone"
                                        class="form-control"
                                        name="timezone"
                                    >
                                        @foreach ($timezones as $key => $timezone)
                                            <option value="{{ $timezone }}" {{ $account->timezone == $timezone ? 'selected' : '' }}>{!! $key !!}</option>
                                        @endforeach
                                    </select>
                                    @error('timezone')
                                        <span class="invalid-feedback d-block mt-2 ml-2" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">{{ __('main.password') . ' (' . __('main.leave_blank_for_no_change') . ')'  }}:</label>
                                    <input
                                        type="password"
                                        id="password"
                                        class="form-control"
                                        placeholder="{{ __('main.password') }}"
                                        name="password"
                                        autocomplete="off"
                                    />
                                    @error('password')
                                        <span class="invalid-feedback d-block mt-2 ml-2" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">{{ __('main.confirm_password') }}:</label>
                                    <input
                                        type="password"
                                        id="password_confirmation"
                                        class="form-control"
                                        placeholder="{{ __('main.confirm_password') }}"
                                        name="password_confirmation"
                                        autocomplete="off"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs nav-underline">
                                    @foreach (array_keys($permissions) as $permission)
                                        <li class="nav-item">
                                            <a
                                            class="nav-link {{ $loop->index == 0 ? 'active' : '' }}"
                                                id="baseIcon-tab21"
                                                data-toggle="tab"
                                                aria-controls="{{ $permission }}"
                                                href="#{{ $permission }}"
                                                aria-expanded="true"
                                                >{{ ucfirst($permission) }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="tab-content px-1 pt-1">
                                    @foreach (array_keys($permissions) as $value)
                                        <div
                                        role="tabpanel"
                                        class="tab-pane {{ $loop->index == 0 ? 'active' : '' }}"
                                        id="{{ $value }}"
                                        {{ $loop->index == 0 ? 'aria-expanded="true"' : '' }}
                                        aria-labelledby="{{ $value }}"
                                        >
                                            <div class="d-flex justify-content-between align-items-center">
                                                @foreach ($permissions[$value] as $permission)

                                                    @php
                                                    $permission_full_name = $permission . ' ' . $value;
                                                    @endphp

                                                    <fieldset class="checkboxsas">
                                                        <label><input type="checkbox" value="{{ $permission_full_name }}" name="permissions[]" {{ in_array($permission_full_name, $account_permissions) ? 'checked' : '' }}/> {{ ucfirst($permission) }} </label>
                                                    </fieldset>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                @error('permissions')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                                @error('permissions.*')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-actions d-flex justify-content-end align-items-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> {{ __('main.save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

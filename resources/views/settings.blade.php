@extends('layouts.app')
@section('title', __('main.settings'))

@section('content')
<div class="row">
    <div class="offset-lg-2 col-lg-8">
        <div class="card">
            <div class="card-header text-center"><h4 class="m-0"><i class="fas fa-cogs mr-2"></i> {{ __('main.settings') }}</h4></div>
            <div class="card-body">
                <form action="{{ route('settings.update') }}" method="POST">
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
                                        value="{{ auth()->user()->username }}"
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
                                            <option value="{{ $timezone }}" {{ auth()->user()->timezone == $timezone ? 'selected' : '' }}>{!! $key !!}</option>
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

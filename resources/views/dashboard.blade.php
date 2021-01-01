@extends('layouts.app')
@section('title', __('main.dashboard'))

@section('content')

@if (!$total_cash_total_all)
    @if ($diff_cash_all > 0)
        <div class="alert alert-warning text-center">
            <strong>{{ __('main.warning') }}</strong> - {{ __('messages.body.total_all_not_equal_total_cash'). '. ' . __('main.cash_is_more_by') . ' ' . number_format($diff_cash_all, 2) . ' ' . applicationSettings('currency') }}!<button class="close" onclick="event.target.parentElement.remove();">&times;</button>
        </div>
    @else
        <div class="alert alert-warning text-center">
            <strong>{{ __('main.warning') }}</strong> - {{ __('messages.body.total_all_not_equal_total_cash'). '. ' . __('main.cash_is_less_by') . ' ' . number_format(abs($diff_cash_all), 2) . ' ' . applicationSettings('currency') }}!<button class="close" onclick="event.target.parentElement.remove();">&times;</button>
        </div>
    @endif
@endif

<div class="row mb-2">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div
                            class="text-xs font-weight-bold text-primary text-uppercase mb-1"
                        >
                            {{ __('main.total_transactions') }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $total_transactions }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div
                            class="text-xs font-weight-bold text-info text-uppercase mb-1"
                        >
                            {{ __('main.total_cash') }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $total_cash }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div
                            class="text-xs font-weight-bold text-success text-uppercase mb-1"
                        >
                            {{ __('main.total_lended') }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $total_lended }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div
                            class="text-xs font-weight-bold text-danger text-uppercase mb-1"
                        >
                            {{ __('main.total_borrowed') }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $total_borrowed }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4 text-left text-md-center">
    <div class="col-md-6 offset-md-3">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div
                            class="text-xs font-weight-bold text-warning text-uppercase mb-1"
                        >
                            {{ __('main.total_all') }}
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $total_all }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @can('create transactions')
        <div class="col-lg-6">
            <add-transaction-form add-transaction-url="{{ route('transactions.store') }}" :change-table="false" :is-more-pages="false"></add-transaction-form>
        </div>
    @endcan
    @can('create cash')
    <div class="col-lg-6">
        <add-cash-form add-cash-url="{{ route('cash.store') }}" :change-table="false" currency="{{ applicationSettings('currency') }}"  :moeny-papers="{{ $money_papers }}"></add-cash-form>
    </div>
    @endcan
</div>

<hr>

<div class="row">
    <div class="col-12">
        <login-log get-log-url="{{ route('ajax.get_login_log') }}"></login-log>
    </div>
</div>
@endsection


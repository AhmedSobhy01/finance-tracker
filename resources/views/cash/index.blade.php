@extends('layouts.app')
@section('title', __('main.current_cash'))

@section('content')
    @can('create cash')
        <add-cash-form add-cash-url="{{ route('cash.store') }}" :change-table="true" currency="{{ applicationSettings('currency') }}" :moeny-papers="{{ $money_papers }}"></add-cash-form>
        <hr>
    @endcan
    <div class="row mb-3">
        <form action="{{ route('cash.index') }}" method="get" id='catForm' class="col-md-6 d-flex">
            <div class="input-container">
                <input
                    type="radio"
                    id="all"
                    class="cursor-pointer"
                    name="cat"
                    value="all"
                    onchange="document.getElementById('catForm').submit()"
                    {{ request()->get('cat') == 'all' ? 'checked' : '' }}
                />
                <label for="all">{{ __("main.all") }}</label>
            </div>
            <div class="input-container">
                <input
                    type="radio"
                    id="not_deleted"
                    class="cursor-pointer"
                    name="cat"
                    value="not_deleted"
                    onchange="document.getElementById('catForm').submit()"
                    {{ request()->get('cat') !== 'all' && request()->get('cat') !== 'deleted' ? 'checked' : '' }}
                />
                <label for="not_deleted">{{ __("main.not_deleted") }}</label>
            </div>
            <div class="input-container">
                <input
                    type="radio"
                    id="deleted"
                    class="cursor-pointer"
                    name="cat"
                    value="deleted"
                    onchange="document.getElementById('catForm').submit()"
                    {{ request()->get('cat') == 'deleted' ? 'checked' : '' }}
                />
                <label for="deleted">{{ __("main.deleted") }}</label>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-striped text-center" id="cashesTable">
            <thead>
                <tr>
                    <th scope="col">{{ __('main.process_serial') }}</th>
                    <th scope="col">{{ __('main.amount') }}</th>
                    <th scope="col">{{ __('main.serial_number') }}</th>
                    <th scope="col">{{ __('main.description') }}</th>
                    <th scope="col">{{ __('main.added_at') }}</th>
                    <th scope="col">{{ __('main.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cashes as $cash)
                    <tr>
                        <th scope="row">{{ $cash->process_serial }}</th>
                        <td>{{ $cash->amount }}</td>
                        <td>{{ $cash->serial_number }}</td>
                        <td class="word-break-all">{{ $cash->description }}</td>
                        <td>{{ $cash->created_at->diffForHumans() }}</td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                <a href="{{ route('process', ['serial' => $cash->process_serial]) }}" class="text-secondary" title="{{ __('main.view_process') }}"><i class="fas fa-eye"></i></a>
                                @can('update cash')
                                    @if ($cash->trashed())
                                        <button class="delete-cash-btn btn py-0 text-info" onclick="changeState(event, {{ $cash->id }})"  title="{{ __('main.restore_cash') }}"><i class="fas fa-undo"></i></button>
                                    @else
                                        <button class="delete-cash-btn btn py-0 text-danger" onclick="changeState(event, {{ $cash->id }})"  title="{{ __('main.delete_cash') }}"><i class="fas fa-ban"></i></button>
                                    @endif
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('styles')
    <style>
        .input-container input[type="radio"] {
            opacity: 0;
            width: 100%;
            height: 42px;
            background-color: blue;
            position: relative;
            z-index: 1;
        }

        .input-container {
            height: 42px;
            width: 100%;
            line-height: 42px;
            text-align: center;
            position: relative;
        }

        .input-container:first-child label {
            border-radius: 5px 0 0 5px;
        }

        .input-container:last-child label {
            border-radius: 0 5px 5px 0;
            border-right: 2px solid #ccc;
        }

        .input-container label {
            width: 100%;
            height: 100%;
            position: absolute;
            border: 2px solid #ccc;
            border-right: inherit;
            top: 0;
            left: 0;
            font-family: arial;
            color: #737373;
        }

        .input-container:nth-child(1) input:checked + label {
            background-color: #4e73df;
            top: 0;
            left: 0;
            border: 2px solid #4e73df !important;
            z-index: 2;
            color: white;
        }

        .input-container:nth-child(2) input:checked + label {
            background-color: #1cc88a;
            top: 0;
            left: 0;
            border: 2px solid #1cc88a !important;
            z-index: 2;
            color: white;
        }

        .input-container:nth-child(3) input:checked + label {
            background-color: #c62828;
            top: 0;
            left: 0;
            border: 2px solid #c62828 !important;
            z-index: 2;
            color: white;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function changeState(e, cashId) {
            e.preventDefault();
            axios
                .delete("{{ route('cash.update') }}", {
                    data: {
                        cashId: cashId
                    }
                })
                .then(res => res.data)
                .then(data => {
                    if (e.target.classList.contains('fa-ban') || e.target.classList.contains('fa-undo')) {
                        e.target.parentElement.title == i18n.main.restore_cash ? e.target.parentElement.title = i18n.main.delete_cash : e.target.parentElement.title = i18n.main.restore_cash;
                        e.target.parentElement.classList.toggle('text-info');
                        e.target.parentElement.classList.toggle('text-danger');
                        e.target.classList.toggle('fa-undo');
                        e.target.classList.toggle('fa-ban');
                    } else {
                        e.target.title == i18n.main.restore_cash ? e.target.title = i18n.main.delete_cash : e.target.title = i18n.main.restore_cash;
                        e.target.classList.toggle('text-info');
                        e.target.classList.toggle('text-danger');
                        e.target.querySelector('i').classList.toggle('fa-undo');
                        e.target.querySelector('i').classList.toggle('fa-ban');
                    }

                    toastr.success(
                        data.response_message,
                        data.response_title
                    );
                })
                .catch(err => {
                    toastr.error(
                        err.response.data.response_message,
                        err.response.data.response_title
                    );
                });
        }
    </script>
@endpush

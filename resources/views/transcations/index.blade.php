@extends('layouts.app')
@section('title', __('main.transactions'))

@section('content')
    @can('create transactions')
        <add-transaction-form add-transaction-url="{{ route('transactions.store') }}" :change-table="true" :is-more-pages="{{ $transactions->hasMorePages() ? 'true' : 'false' }}"></add-transaction-form>
        <hr>
    @endcan
    <div class="row mb-3">
        <form action="{{ route('transactions.index') }}" method="get" id='catForm' class="col-md-6 d-flex">
            <div class="input-container">
                <input
                    type="radio"
                    id="all"
                    class="cursor-pointer"
                    name="cat"
                    value="all"
                    onchange="document.getElementById('catForm').submit()"
                    {{ request()->get('cat') !== 'paid' && request()->get('cat') !== 'unpaid' ? 'checked' : '' }}
                />
                <label for="all">{{ __("main.all") }}</label>
            </div>
            <div class="input-container">
                <input
                    type="radio"
                    id="income"
                    class="cursor-pointer"
                    name="cat"
                    value="income"
                    onchange="document.getElementById('catForm').submit()"
                    {{ request()->get('cat') == 'income' ? 'checked' : '' }}
                />
                <label for="income">{{ __("main.income") }}</label>
            </div>
            <div class="input-container">
                <input
                    type="radio"
                    id="expense"
                    class="cursor-pointer"
                    name="cat"
                    value="expense"
                    onchange="document.getElementById('catForm').submit()"
                    {{ request()->get('cat') == 'expense' ? 'checked' : '' }}
                />
                <label for="expense">{{ __("main.expense") }}</label>
            </div>
        </form>
        <div class="col-md-6 mt-3 mt-md-0">
            <div class="d-flex justify-content-end">
                {{ $transactions->withQueryString()->links() }}
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table text-center" id="transactionsTable">
            <thead>
                <tr>
                    <th scope="col">{{ __('main.process_serial') }}</th>
                    <th scope="col">{{ __('main.type') }}</th>
                    <th scope="col">{{ __('main.amount') }}</th>
                    <th scope="col">{{ __('main.description') }}</th>
                    <th scope="col">{{ __('main.created_at') }}</th>
                    <th scope="col">{{ __('main.actions') }}</th>
                </tr>
            </thead>
            <tbody class="text-white">
                @foreach ($transactions as $transaction)
                    <tr class="{{ $transaction->getRawOriginal('type') == 1 ? 'bg-success' : 'bg-danger' }}">
                        <th scope="row">{{ $transaction->process_serial }}</th>
                        <td>{{ $transaction->type }}</td>
                        <td>{{ $transaction->amount }}</td>
                        <td class="word-break-all">{{ $transaction->description }}</td>
                        <td>{{ $transaction->created_at->format('Y-m-d h:i:s A') }}</td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                <a href="{{ route('process', ['serial' => $transaction->process_serial]) }}" class="text-white" title="{{ __('main.view_process') }}"><i class="fas fa-eye"></i></a>
                                @can('delete transactions')
                                    <button class="delete-transaction-btn btn py-0 text-white" onclick="deleteTransaction(event, {{ $transaction->id }})" title="{{ __('main.delete_transaction') }}"><i class="fas fa-ban"></i></button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center align-items-center">
        {{ $transactions->links() }}
    </div>
@endsection

@push('styles')
    {{-- Main Styling Edit --}}
    <style>
        .bg-success {
            background-color: #388E3C !important;
        }

        .bg-danger {
            background-color: #C62828 !important;
        }
    </style>

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
        function deleteTransaction(e, transactionId) {
            e.preventDefault();
            Swal.fire({
                title: "{{ __('main.are_you_sure') }}",
                text: "{!! __('main.not_revert') !!}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{ __('main.yes_delete_it') }}",
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return axios
                        .delete("{{ route('transactions.destroy') }}", {
                            data: {
                                transactionId: transactionId
                            }
                        })
                        .then(res => res.data)
                        .then(data => {
                            e.target.closest("tr").remove();
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
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        }
    </script>
@endpush

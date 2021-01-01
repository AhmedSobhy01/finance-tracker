@extends('layouts.app')
@section('title', __('main.borrowings'))

@section('content')
    @can('create dues')
        <add-borrowing-form add-borrowing-url="{{ route('borrowings.store') }}" :add-to-table={{ request()->get('cat') !== 'paid' ? 'true' : 'false' }} :is-more-pages="{{ $borrowings->hasMorePages() ? 'true' : 'false' }}" :people="{{ $people }}"></add-borrowing-form>
        <hr>
    @endcan
    <div class="row mb-3">
        <form action="{{ route('borrowings.index') }}" method="get" id='catForm' class="col-md-6 d-flex">
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
                    id="unpaid"
                    class="cursor-pointer"
                    name="cat"
                    value="unpaid"
                    onchange="document.getElementById('catForm').submit()"
                    {{ request()->get('cat') == 'unpaid' ? 'checked' : '' }}
                />
                <label for="unpaid">{{ __("main.unpaid") }}</label>
            </div>
            <div class="input-container">
                <input
                    type="radio"
                    id="paid"
                    class="cursor-pointer"
                    name="cat"
                    value="paid"
                    onchange="document.getElementById('catForm').submit()"
                    {{ request()->get('cat') == 'paid' ? 'checked' : '' }}
                />
                <label for="paid">{{ __("main.paid") }}</label>
            </div>
        </form>
        <div class="col-md-6 mt-3 mt-md-0">
            <div class="d-flex justify-content-end">
                {{ $borrowings->withQueryString()->links() }}
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped text-center" id="borrowingsTable">
            <thead>
                <tr>
                    <th scope="col">{{ __('main.process_serial') }}</th>
                    <th scope="col">{{ __('main.person') }}</th>
                    <th scope="col">{{ __('main.amount') }}</th>
                    <th scope="col">{{ __('main.description') }}</th>
                    <th scope="col">{{ __('main.created_at') }}</th>
                    <th scope="col">{{ __('main.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($borrowings as $borrowing)
                    <tr>
                        <th scope="row">{{ $borrowing->process_serial }}</th>
                        <td><a href="{{ route('people.show', $borrowing->person->id) }}" class="text-decoration-none text-secondary">{{ $borrowing->person->name }}</a></td>
                        <td>{{ $borrowing->amount }}</td>
                        <td class="word-break-all">{{ $borrowing->description }}</td>
                        <td>{{ $borrowing->created_at->format('Y-m-d h:i:s A') }}</td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                @can('dues.update')
                                    @if (!$borrowing->paid_at)
                                        <button class="paid-borrowing-btn btn py-0 text-success" onclick="changeState(event, {{ $borrowing->id }})" title="{{ __('main.borrowing_paid') }}"><i class="fas fa-check"></i></button>
                                    @else
                                        <button class="paid-borrowing-btn btn py-0 text-danger" onclick="changeState(event, {{ $borrowing->id }})" title="{{ __('main.borrowing_unpaid') }}"><i class="fas fa-times"></i></button>
                                    @endif
                                @endcan
                                <a href="{{ route('process', ['serial' => $borrowing->process_serial]) }}" class="text-secondary" title="{{ __('main.view_process') }}"><i class="fas fa-eye"></i></a>
                                @can('delete dues')
                                    <button class="delete-borrowing-btn btn py-0 text-danger" onclick="deleteBorrowing(event, {{ $borrowing->id }})" title="{{ __('main.delete_borrowing') }}"><i class="fas fa-ban"></i></button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center align-items-center">
        {{ $borrowings->withQueryString()->links() }}
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
            background-color: #c62828;
            top: 0;
            left: 0;
            border: 2px solid #c62828 !important;
            z-index: 2;
            color: white;
        }

        .input-container:nth-child(3) input:checked + label {
            background-color: #1cc88a;
            top: 0;
            left: 0;
            border: 2px solid #1cc88a !important;
            z-index: 2;
            color: white;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function changeState(e, borrowingId) {
            e.preventDefault();
            axios
                .patch("{{ route('borrowings.update') }}", {
                    dueId: borrowingId
                })
                .then(res => res.data)
                .then(data => {
                    if (e.target.classList.contains('fa-check') || e.target.classList.contains('fa-times')) {
                        e.target.parentElement.title == i18n.main.borrowing_paid ? e.target.parentElement.title = i18n.main.borrowing_unpaid : e.target.parentElement.title = i18n.main.borrowing_paid;
                        e.target.parentElement.classList.toggle('text-success');
                        e.target.parentElement.classList.toggle('text-danger');
                        e.target.classList.toggle('fa-check');
                        e.target.classList.toggle('fa-times');
                    }else {
                        e.target.title == i18n.main.borrowing_paid ? e.target.title = i18n.main.borrowing_unpaid : e.target.title = i18n.main.borrowing_paid;
                        e.target.classList.toggle('text-success');
                        e.target.classList.toggle('text-danger');
                        e.target.querySelector('i').classList.toggle('fa-check');
                        e.target.querySelector('i').classList.toggle('fa-times');
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

        function deleteBorrowing(e, borrowingId) {
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
                        .delete("{{ route('borrowings.destroy') }}", {
                            data: {
                                dueId: borrowingId
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


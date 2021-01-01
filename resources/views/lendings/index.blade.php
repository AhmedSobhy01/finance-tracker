@extends('layouts.app')
@section('title', __('main.lendings'))

@section('content')
    @can('create dues')
        <add-lending-form add-lending-url="{{ route('lendings.store') }}" :add-to-table={{ request()->get('cat') !== 'paid' ? 'true' : 'false' }} :is-more-pages="{{ $lendings->hasMorePages() ? 'true' : 'false' }}" :people="{{ $people }}"></add-lending-form>
        <hr>
    @endcan
    <div class="row mb-3">
        <form action="{{ route('lendings.index') }}" method="get" id='catForm' class="col-md-6 d-flex">
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
                {{ $lendings->withQueryString()->links() }}
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped text-center" id="lendingsTable">
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
                @foreach ($lendings as $lending)
                    <tr>
                        <th scope="row">{{ $lending->process_serial }}</th>
                        <td><a href="{{ route('people.show', $lending->person->id) }}" class="text-decoration-none text-secondary">{{ $lending->person->name }}</a></td>
                        <td>{{ $lending->amount }}</td>
                        <td class="word-break-all">{{ $lending->description }}</td>
                        <td>{{ $lending->created_at->format('Y-m-d h:i:s A') }}</td>
                        <td>
                            <div class="d-flex justify-content-center align-items-center">
                                @can('update dues')
                                    @if (!$lending->paid_at)
                                        <button class="paid-lending-btn btn py-0 text-success" onclick="changeState(event, {{ $lending->id }})" title="{{ __('main.lending_paid') }}"><i class="fas fa-check"></i></button>
                                    @else
                                        <button class="paid-lending-btn btn py-0 text-danger" onclick="changeState(event, {{ $lending->id }})" title="{{ __('main.lending_unpaid') }}"><i class="fas fa-times"></i></button>
                                    @endif
                                @endcan
                                <a href="{{ route('process', ['serial' => $lending->process_serial]) }}" class="text-secondary" title="{{ __('main.view_process') }}"><i class="fas fa-eye"></i></a>
                                @can('delete cash')
                                    <button class="delete-lending-btn btn py-0 text-danger" onclick="deleteLending(event, {{ $lending->id }})" title="{{ __('main.delete_lending') }}"><i class="fas fa-ban"></i></button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center align-items-center">
        {{ $lendings->withQueryString()->links() }}
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
        function changeState(e, lendingId) {
            e.preventDefault();
            axios
                .patch("{{ route('lendings.update') }}", {
                    dueId: lendingId
                })
                .then(res => res.data)
                .then(data => {
                    if (e.target.classList.contains('fa-check') || e.target.classList.contains('fa-times')) {
                        e.target.parentElement.title == i18n.main.lending_paid ? e.target.parentElement.title = i18n.main.lending_unpaid : e.target.parentElement.title = i18n.main.lending_paid;
                        e.target.parentElement.classList.toggle('text-success');
                        e.target.parentElement.classList.toggle('text-danger');
                        e.target.classList.toggle('fa-check');
                        e.target.classList.toggle('fa-times');
                    }else {
                        e.target.title == i18n.main.lending_paid ? e.target.title = i18n.main.lending_unpaid : e.target.title = i18n.main.lending_paid;
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

        function deleteLending(e, lendingId) {
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
                        .delete("{{ route('lendings.destroy') }}", {
                            data: {
                                dueId: lendingId
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


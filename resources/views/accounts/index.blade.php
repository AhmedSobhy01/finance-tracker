@extends('layouts.app')
@section('title', __('main.accounts'))

@section('content')
    @can('create accounts')
        <add-account-form add-account-url="{{ route('accounts.store') }}"></add-account-form>
        <hr>
    @endcan
    <div class="d-flex justify-content-center align-items-center">
        {{ $accounts->links() }}
    </div>
    <div class="table-responsive">
        <table class="table table-striped text-center" id="accountsTable">
            <thead>
                <tr>
                    <th scope="col">{{ __('main.id') }}</th>
                    <th scope="col">{{ __('main.username') }}</th>
                    <th scope="col">{{ __('main.created_at') }}</th>
                    <th scope="col">{{ __('main.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accounts as $account)
                    <tr>
                        <th scope="row">{{ $account->id }}</th>
                        <td>{{ $account->username }}</td>
                        <td>{{ $account->created_at->format('Y-m-d h:i:s A') }}</td>
                        <td>
                            @can('update accounts')
                                <a href="{{ route('accounts.edit', $account->id) }}" class="text-warning" title="{{ __('main.edit_account') }}"><i class="fas fa-user-edit"></i></a>
                            @endcan
                            @can('delete accounts')
                                <button class="delete-account-btn btn py-0 text-danger" onclick="deleteAccount(event, {{ $account->id }})" title="{{ __('main.delete_account') }}"><i class="fas fa-ban"></i></button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center align-items-center">
        {{ $accounts->links() }}
    </div>
@endsection

@push('scripts')
    <script>
        function deleteAccount(e, accountId) {
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
                        .delete("{{ route('accounts.destroy') }}", {
                            data: {
                                accountId: accountId
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



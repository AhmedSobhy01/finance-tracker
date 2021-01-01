@extends('layouts.app')
@section('title', __('main.process') . ' #' . strtoupper(request()->serial))

@section('content')
<div class="card shadow mb-4" id="summaryCard">
    <div class="card-header py-3 d-flex justify-content-center align-items-center">
        <h3 class="m-0 font-weight-bold text-info">{{ __('main.summary') }}</h3>
        @if($table_type !== 0)
            <div class="d-flex justify-content-center align-items-center noprint">
                <button type="button" class="btn py-0" onclick="printElm('summaryCard')"><i class="fas fa-print fa-lg"></i></button>
                <div class="border border-dark rounded py-1">
                    @if($table_type == 1)
                        <button class="btn py-0 text-danger"onclick="deleteTransaction(event, {{ $process->id }})" title="{{ __('main.delete_transaction') }}"><i class="fas fa-ban"></i></button>
                    @elseif($table_type == 2)
                        @if (!$process->paid_at)
                            <button class="btn py-0 text-success" onclick="changeState(event, {{ $process->id }})" title="{{ __('main.lending_paid') }}"><i class="fas fa-check"></i></button>
                        @else
                            <button class="btn py-0 text-danger" onclick="changeState(event, {{ $process->id }})" title="{{ __('main.lending_unpaid') }}"><i class="fas fa-times"></i></button>
                        @endif
                        <button class="btn py-0 text-danger" onclick="deleteDue(event, {{ $process->id }})" title="{{ __('main.delete_lending') }}"><i class="fas fa-ban"></i></button>
                    @elseif($table_type == 3)
                        @if ($process->trashed())
                            <button class="btn py-0 text-info" onclick="changeState(event, {{ $process->id }})"  title="{{ __('main.delete_cash') }}"><i class="fas fa-undo"></i></button>
                        @else
                            <button class="btn py-0 text-danger" onclick="changeState(event, {{ $process->id }})"  title="{{ __('main.delete_cash') }}"><i class="fas fa-ban"></i></button>
                        @endif
                    @endif
                </div>
            </div>
        @endif
    </div>
    <div class="card-body">
        @if ($table_type == 0)
            <div class="h3 text-center">{{ __('main.no_data_found_for') }} {{ strtoupper(request()->serial) }}</div>
        @elseif ($table_type == 1)
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.process_serial') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    <div class="d-flex align-items-center">
                        {{ strtoupper($process->process_serial) }}
                        {!! '<img class="ml-3" src="data:image/png;base64,' . DNS1D::getBarcodePNG($process->process_serial, 'C39+', 1.6, 18) . '" alt="barcode"   />' !!}
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.id_in_db_table') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ $process->id }}
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.process_type') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ __('main.transacation') }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.created_at') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ $process->created_at->diffForHumans() . ' ' . __('main.at') . ' ' . $process->created_at->format('Y-m-d h:i:s A') }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.updated_at') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ $process->updated_at->diffForHumans() . ' ' . __('main.at') . ' ' . $process->updated_at->format('Y-m-d h:i:s A') }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.process_url') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {!! QrCode::size(100)->generate(Request::url()) !!}
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.type') }}:
                </div>
                <div class="col-md-9 font-weight-bold {{ $process->getRawOriginal('type') == 0 ? 'text-danger' : 'text-success' }}">
                    {{ $process->type }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.amount') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ $process->amount }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.description') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ $process->description }}
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.total_after') }}:
                </div>
                <div class="col-md-9 font-weight-bold text-primary">
                    {{ number_format(App\Models\Transaction::select(DB::raw('(SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) - SUM(CASE WHEN type = 0 THEN amount ELSE 0 END)) as total'))->where('id', '<=', $process->id)->get()[0]->total ?? 0, 2) . ' ' . applicationSettings('currency') }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.total_incomes_after') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ number_format(App\Models\Transaction::select(DB::raw('SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) as incomes'))->where('id', '<=', $process->id)->get()[0]->incomes ?? 0, 2) . ' ' . applicationSettings('currency') }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.total_expenses_after') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ number_format(App\Models\Transaction::select(DB::raw('SUM(CASE WHEN type = 0 THEN amount ELSE 0 END) as incomes'))->where('id', '<=', $process->id)->get()[0]->incomes ?? 0, 2) . ' ' . applicationSettings('currency') }}
                </div>
            </div>
        @elseif ($table_type == 2)
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.process_serial') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    <div class="d-flex align-items-center">
                        {{ strtoupper($process->process_serial) }}
                        {!! '<img class="ml-3" src="data:image/png;base64,' . DNS1D::getBarcodePNG($process->process_serial, 'C39+', 1.6, 18) . '" alt="barcode"   />' !!}
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.id_in_db_table') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ $process->id }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.process_url') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {!! QrCode::size(100)->generate(Request::url()) !!}
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.process_type') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ __('main.due') }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.created_at') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ $process->created_at->diffForHumans() . ' ' . __('main.at') . ' ' . $process->created_at->format('Y-m-d h:i:s A') }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.updated_at') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ $process->updated_at->diffForHumans() . ' ' . __('main.at') . ' ' . $process->updated_at->format('Y-m-d h:i:s A') }}
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.due_type') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ $process->getRawOriginal('type') == 0 ? __('main.lending') : __('main.borrowing') }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.lender') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {!! $process->getRawOriginal('type') == 0 ? applicationSettings('owner') : '<a href="' . route('people.show', $process->person->id) . '" class="text-secondary text-decoration-none">' . $process->person->name . '</a>' !!}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.borrower') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {!! $process->getRawOriginal('type') == 0 ? '<a href="' . route('people.show', $process->person->id) . '" class="text-secondary text-decoration-none">' . $process->person->name . '</a>' : applicationSettings('owner') !!}
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.amount') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ $process->amount }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.description') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ $process->description }}
                </div>
            </div>
            <div class="row mb-3" id="paid">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.paid') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    @if ($process->paid_at)
                        <i class="far fa-check-circle text-success"></i>
                    @else
                        <i class="far fa-times-circle text-danger"></i>
                    @endif
                </div>
            </div>
            @if ($process->paid_at)
                <div class="row mb-3">
                    <div class="col-md-3 text-left text-md-right">
                        {{ __('main.paid_at') }}:
                    </div>
                    <div class="col-md-9 font-weight-bold">
                        {{ $process->paid_at->diffForHumans() . ' at ' . $process->paid_at->format('Y-m-d h:i:s A')}}
                    </div>
                </div>
            @endif
            <hr>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.total_lended_until') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ number_format(App\Models\Due::select(DB::raw('SUM(CASE WHEN type = 0 THEN amount ELSE 0 END) as lendings'))->where('id', '<=', $process->id)->get()[0]->lendings ?? 0, 2) . ' ' . applicationSettings('currency') }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.total_borrowed_until') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ number_format(App\Models\Due::select(DB::raw('SUM(CASE WHEN type = 1 THEN amount ELSE 0 END) as borrowings'))->where('id', '<=', $process->id)->get()[0]->borrowings ?? 0, 2) . ' ' . applicationSettings('currency') }}
                </div>
            </div>
        @elseif ($table_type == 3)
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.process_serial') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    <div class="d-flex align-items-center">
                        {{ strtoupper($process->process_serial) }}
                        {!! '<img class="ml-3" src="data:image/png;base64,' . DNS1D::getBarcodePNG($process->process_serial, 'C39+', 1.6, 18) . '" alt="barcode"   />' !!}
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.id_in_db_table') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ $process->id }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.process_url') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {!! QrCode::size(100)->generate(Request::url()) !!}
                </div>
            </div>
            <hr>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.process_type') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ __('main.cash') }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.created_at') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ $process->created_at->diffForHumans() . ' ' . __('main.at') . ' ' . $process->created_at->format('Y-m-d h:i:s A') }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.updated_at') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ $process->updated_at->diffForHumans() . ' ' . __('main.at') . ' ' . $process->updated_at->format('Y-m-d h:i:s A') }}
                </div>
            </div>
            <div class="row mb-3" id="available">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.available') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    @if (!$process->trashed())
                        <i class="far fa-check-circle text-success"></i>
                    @else
                        <i class="far fa-times-circle text-danger"></i>
                    @endif
                </div>
            </div>
            @if ($process->trashed())
                <div class="row mb-3">
                    <div class="col-md-3 text-left text-md-right">
                        {{ __('main.deleted_at') }}:
                    </div>
                    <div class="col-md-9 font-weight-bold">
                        {{ $process->deleted_at->diffForHumans() . ' at ' . $process->deleted_at->format('Y-m-d h:i:s A')}}
                    </div>
                </div>
            @endif
            <hr>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.amount') }}:
                </div>
                <div class="col-md-9 font-weight-bold text-primary">
                    {{ $process->amount }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.serial_number') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ $process->serial_number }}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 text-left text-md-right">
                    {{ __('main.description') }}:
                </div>
                <div class="col-md-9 font-weight-bold">
                    {{ $process->description }}
                </div>
            </div>
            <hr>
        @endif
    </div>
</div>
@endsection

@push('scripts')
    @if($table_type == 1)
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
                                Swal.fire(data.response_title, data.response_message, 'success').then (result => window.location.href = "{{ route('transactions.index') }}");
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
    @elseif($table_type == 2)
        @if($process->getRawOriginal('type') == 0)
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

                            if (!data.data.paid) {
                                document.querySelector("#paid").nextElementSibling.remove();
                            } else {
                                let a = document.createElement("div"),
                                    b = document.createElement("div"),
                                    c = document.createElement("div"),
                                    d = document.createTextNode("{{ __('main.paid_at') }}:"),
                                    e = document.createTextNode(data.data.paid_at);

                                a.classList.add('row', 'mb-3');
                                b.classList.add('col-md-3', 'text-left', 'text-md-right');
                                c.classList.add('col-md-9', 'font-weight-bold');

                                b.appendChild(d);
                                c.appendChild(e);
                                a.appendChild(b);
                                a.appendChild(c);

                                document.querySelector("#paid").parentNode.insertBefore(a, document.querySelector("#paid").nextSibling)
                            }

                            document.querySelector("#paid .col-md-9 i").classList.toggle('fa-check-circle');
                            document.querySelector("#paid .col-md-9 i").classList.toggle('fa-times-circle');
                            document.querySelector("#paid .col-md-9 i").classList.toggle('text-success');
                            document.querySelector("#paid .col-md-9 i").classList.toggle('text-danger');

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

                function deleteDue(e, lendingId) {
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
                                    Swal.fire(data.response_title, data.response_message, 'success').then (result => window.location.href = "{{ route('lendings.index') }}");
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
        @else
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
                            } else {
                                e.target.title == i18n.main.borrowing_paid ? e.target.title = i18n.main.borrowing_unpaid : e.target.title = i18n.main.borrowing_paid;
                                e.target.classList.toggle('text-success');
                                e.target.classList.toggle('text-danger');
                                e.target.querySelector('i').classList.toggle('fa-check');
                                e.target.querySelector('i').classList.toggle('fa-times');
                            }

                            if (!data.data.paid) {
                                document.querySelector("#paid").nextElementSibling.remove();
                            } else {
                                let a = document.createElement("div"),
                                    b = document.createElement("div"),
                                    c = document.createElement("div"),
                                    d = document.createTextNode("{{ __('main.paid_at') }}:"),
                                    e = document.createTextNode(data.data.paid_at);

                                a.classList.add('row', 'mb-3');
                                b.classList.add('col-md-3', 'text-left', 'text-md-right');
                                c.classList.add('col-md-9', 'font-weight-bold');

                                b.appendChild(d);
                                c.appendChild(e);
                                a.appendChild(b);
                                a.appendChild(c);

                                document.querySelector("#paid").parentNode.insertBefore(a, document.querySelector("#paid").nextSibling)
                            }

                            document.querySelector("#paid .col-md-9 i").classList.toggle('fa-check-circle');
                            document.querySelector("#paid .col-md-9 i").classList.toggle('fa-times-circle');
                            document.querySelector("#paid .col-md-9 i").classList.toggle('text-success');
                            document.querySelector("#paid .col-md-9 i").classList.toggle('text-danger');

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

                function deleteDue(e, borrowingId) {
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
                                    Swal.fire(data.response_title, data.response_message, 'success').then (result => window.location.href = "{{ route('borrowings.index') }}");
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
        @endif
    @elseif($table_type == 3)
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

                        if (data.data.available) {
                            document.querySelector("#available").nextElementSibling.remove();
                        } else {
                            let a = document.createElement("div"),
                                b = document.createElement("div"),
                                c = document.createElement("div"),
                                d = document.createTextNode("{{ __('main.deleted_at') }}:"),
                                e = document.createTextNode(data.data.deleted_at);

                            a.classList.add('row', 'mb-3');
                            b.classList.add('col-md-3', 'text-left', 'text-md-right');
                            c.classList.add('col-md-9', 'font-weight-bold');

                            b.appendChild(d);
                            c.appendChild(e);
                            a.appendChild(b);
                            a.appendChild(c);

                            document.querySelector("#available").parentNode.insertBefore(a, document.querySelector("#available").nextSibling)
                        }

                        document.querySelector("#available .col-md-9 i").classList.toggle('fa-check-circle');
                        document.querySelector("#available .col-md-9 i").classList.toggle('fa-times-circle');
                        document.querySelector("#available .col-md-9 i").classList.toggle('text-success');
                        document.querySelector("#available .col-md-9 i").classList.toggle('text-danger');

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
    @endif
@endpush

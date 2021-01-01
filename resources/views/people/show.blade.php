@extends('layouts.app')
@section('title', $person->name . ' ' .__('main.dues'))

@section('content')
<div class="card shadow mb-4" id="personCard">
    <div class="card-header py-3 d-flex justify-content-center align-items-center">
        <h3 class="m-0 font-weight-bold text-info">{{ $person->name }}</h3>
        <button type="button" class="btn py-0 noprint" onclick="printElm('personCard')"><i class="fas fa-print fa-lg"></i></button>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-3 text-left text-md-right">
                {{ __('main.id') }}:
            </div>
            <div class="col-md-9 font-weight-bold">
                {{ strtoupper($person->id) }}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 text-left text-md-right">
                {{ __('main.name') }}:
            </div>
            <div class="col-md-9 font-weight-bold">
                {{ $person->name }}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 text-left text-md-right">
                {{ __('main.created_at') }}:
            </div>
            <div class="col-md-9 font-weight-bold">
                {{ $person->created_at->diffForHumans() . ' ' . __('main.at') . ' ' . $person->created_at->format('Y-m-d h:i:s A') }}
            </div>
        </div>
        <hr>
        <div class="row mb-3">
            <div class="col-md-3 text-left text-md-right">
                {{ __('main.total_unpaid_borrowed') }}:
            </div>
            <div class="col-md-9 font-weight-bold">
                {{ $total_unpaid_borrowed }}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 text-left text-md-right">
                {{ __('main.total_unpaid_lended') }}:
            </div>
            <div class="col-md-9 font-weight-bold">
                {{ $total_unpaid_lended }}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 text-left text-md-right">
                {{ __('main.total_unpaid_borrowed_lended') }}:
            </div>
            <div class="col-md-9 font-weight-bold">
                {{ $total_unpaid_borrowed_lended }}
            </div>
        </div>
        <hr>
        <div class="row mb-3">
            <div class="col-md-3 text-left text-md-right">
                {{ __('main.total_paid_borrowed') }}:
            </div>
            <div class="col-md-9 font-weight-bold">
                {{ $total_paid_borrowed }}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 text-left text-md-right">
                {{ __('main.total_paid_lended') }}:
            </div>
            <div class="col-md-9 font-weight-bold">
                {{ $total_paid_lended }}
            </div>
        </div>
        <hr>
        <div class="row mb-3">
            <div class="col-md-3 text-left text-md-right">
                {{ __('main.total_borrowed') }}:
            </div>
            <div class="col-md-9 font-weight-bold">
                {{ $total_borrowed }}
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-3 text-left text-md-right">
                {{ __('main.total_lended') }}:
            </div>
            <div class="col-md-9 font-weight-bold">
                {{ $total_lended }}
            </div>
        </div>
        <hr>
        <div class="not-paid mb-3">
            <div class="h2 mb-3"><i class="far fa-times-circle fa-sm text-danger"></i> {{ __('main.not_paid') }}:</div>
            @forelse ($person->dues as $due)
                @if (!$due->paid_at)
                    <div class="list-group">
                        <div class="list-group-item mb-2 a__link__no__drag" draggable="false">
                            <a href="{{ route('process', ['serial' => $due->process_serial]) }}" class="row text-decoration-none">
                                <h3 class="list-group-item-heading col-md-8">{{ $person->name . ' ' . $due->type . ' ' . $due->amount }}</h3>
                                <p class="list-group-item-text col-md-4">{{ $due->description }}</p>
                            </a>
                            <p class="list-group-item-text">
                                {{ '#' . $due->process_serial }}
                                <button class="btn copy-process-serial-btn" data-process-serial="{{ $due->process_serial }}" data-toggle="tooltip"><i class="fas fa-copy"></i></button>
                            </p>
                        </div>
                    </div>
                @endif
            @empty
                <div class="h5 ml-5">{{ __('main.nothing_to_show') }}</div>
            @endforelse
        </div>
        <div class="paid">
            <div class="h2 mb-3"><i class="far fa-check-circle fa-sm text-success"></i> {{ __('main.paid') }}:</div>
            @forelse ($person->dues as $due)
                @if ($due->paid_at)
                    <div class="list-group">
                        <div class="list-group-item mb-2 a__link__no__drag" draggable="false">
                            <a href="{{ route('process', ['serial' => $due->process_serial]) }}" class="row text-decoration-none">
                                <h3 class="list-group-item-heading col-md-8">{{ $person->name . ' ' . $due->type . ' ' . $due->amount }}</h3>
                                <p class="list-group-item-text col-md-4">{{ __('main.paid_at') . ': ' . $due->paid_at->format('Y-m-d h:i:s A') }}</p>
                            </a>
                            <p class="list-group-item-text">
                                {{ '#' . $due->process_serial }}
                                <button class="btn copy-process-serial-btn" data-process-serial="{{ $due->process_serial }}" data-toggle="tooltip"><i class="fas fa-copy"></i></button>
                            </p>
                        </div>
                    </div>
                @endif
            @empty
                <div class="h5 ml-5">{{ __('main.nothing_to_show') }}</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('styles')
    <style>
        div.list-group-item {
            color: #555;
            text-decoration: none;
        }
        div.list-group-item a {
            color: #555;
            text-decoration: none;
        }
        .list-group-item-heading {
            margin-top: 0;
            margin-bottom: 5px;
        }
        div.list-group-item .list-group-item-heading {
            color: #333;
        }
        .list-group-item-text {
            margin-bottom: 0;
            line-height: 1.3;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Popovers for copy buttons
        $('.copy-process-serial-btn').tooltip({
            'trigger': "hover",
            'placement': "right",
            'title': i18n.main.copy_to_clipboard,
        });

        document.querySelectorAll('.copy-process-serial-btn').forEach(btn => {
            btn.addEventListener('click', e => {
                let processSerial = btn.dataset.processSerial,
                    textArea = document.createElement("textarea");

                textArea.style.position = 'fixed';
                textArea.style.top = 0;
                textArea.style.left = 0;
                textArea.style.width = '2em';
                textArea.style.height = '2em';
                textArea.style.padding = 0;
                textArea.style.border = 'none';
                textArea.style.outline = 'none';
                textArea.style.boxShadow = 'none';
                textArea.style.background = 'transparent';

                textArea.value = processSerial;

                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();

                try {
                    let s = document.execCommand('copy');
                    $(`[data-process-serial="${processSerial}"]`).popover({
                        'placement': 'right',
                        'content': i18n.main.copied_to_clipboard,
                    }).tooltip('hide').popover('show');
                    let tmp = setTimeout(() =>  $(`[data-process-serial="${processSerial}"]`).popover('hide'), 5000);
                } catch (err) {
                    $(`[data-process-serial="${processSerial}"]`).popover({
                        'placement': 'right',
                        'content': i18n.main.wasnt_copied_to_clipboard,
                    }).tooltip('hide').popover('show');
                    let tmp = setTimeout(() =>  $(`[data-process-serial="${processSerial}"]`).popover('hide'), 5000);
                }

                document.body.removeChild(textArea);
            });
        });
    </script>
@endpush

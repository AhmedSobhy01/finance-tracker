@extends('layouts.app')
@section('title', __('main.application_settings'))

@section('content')
<div class="row mb-4">
    <div class="offset-lg-2 col-lg-8">
        <div class="card">
            <div class="card-header text-center"><h4 class="m-0"><i class="fas fa-cogs mr-2"></i> {{ __('main.application_settings') }}</h4></div>
            <div class="card-body">
                <form action="{{ route('application.settings.update') }}" method="POST">
                    @csrf
                    @method('patch')
                    <div class="form-body">
                        @include('partials._success')
                        @include('partials._error')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="currency">{{ __('main.currency') }}:</label>
                                    <input
                                        type="text"
                                        id="currency"
                                        class="form-control"
                                        placeholder="{{ __('main.currency') }}"
                                        name="currency"
                                        value="{{ applicationSettings('currency') }}"
                                        autocomplete="off"
                                    />
                                    @error('currency')
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
                                    <label for="pagination_count">{{ __('main.pagination_count') }}:</label>
                                    <input
                                        type="number"
                                        step="1"
                                        min="5"
                                        id="pagination_count"
                                        class="form-control"
                                        placeholder="{{ __('main.pagination_count') }}"
                                        name="pagination_count"
                                        value="{{ applicationSettings('pagination_count') }}"
                                        autocomplete="off"
                                    />
                                    @error('pagination_count')
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
                                    <label for="owner">{{ __('main.owner') }}:</label>
                                    <input
                                        type="text"
                                        id="owner"
                                        class="form-control"
                                        placeholder="{{ __('main.owner') }}"
                                        name="owner"
                                        value="{{ applicationSettings('owner') }}"
                                        autocomplete="off"
                                    />
                                    @error('owner')
                                        <span class="invalid-feedback d-block mt-2 ml-2" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
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
<div class="row pb-5">
    <div class="offset-lg-2 col-lg-8">
        <div class="card border-danger">
            <div class="card-header text-center bg-danger text-white"><h4 class="m-0"><i class="fas fa-exclamation-triangle mr-2"></i> {{ __('main.danger_area') }}</h4></div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 font-weight-bold d-flex justify-content-center align-items-center">{{ __('main.clear_cache') }}:</span></div>
                    <div class="col-md-6">
                        <button id="clearCache" class="btn btn-block border-danger text-danger mt-3 mt-md-0" title="{{ __('main.clear_cache') }}">{{ __('main.clear_cache') }}</button>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6 font-weight-bold d-flex justify-content-center align-items-center">{{ __('main.start_date') }}:&nbsp;<span id="startDate">{{ applicationSettings('start_date') }}</span></div>
                    <div class="col-md-6">
                        <button id="setStartDate" class="btn btn-block border-danger text-danger mt-3 mt-md-0" title="{{ __('main.set_today_as_start') }}">{{ __('main.set_today_as_start') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.getElementById("setStartDate").addEventListener("click", () => {
            Swal.fire({
                title: "{{ __('main.are_you_sure') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "{{ __('main.yes_reset_it') }}",
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return axios
                        .post("{{ route('ajax.set_start_date') }}")
                        .then(res => res.data)
                        .then(data => {
                            document.getElementById("startDate").innerHTML =
                                data.data.startDate;
                            toastr.success(
                                "{{ __('messages.body.start_date_updated_successfully') }}",
                                "{{ __('messages.title.success') }}"
                            );
                        })
                        .catch(err =>
                            toastr.error(
                                err.response.data.response_message,
                                err.response.data.response_title
                            )
                        );
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        });
        document.getElementById("clearCache").addEventListener("click", () => {
            Swal.fire({
                title: "{{ __('main.are_you_sure') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "{{ __('main.yes_reset_it') }}",
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return axios
                        .post("{{ route('ajax.clear_cache') }}")
                        .then(res => res.data)
                        .then(data => {
                            toastr.success(
                                "{{ __('messages.body.cache_cleared_successfully') }}",
                                "{{ __('messages.title.success') }}"
                            );
                        })
                        .catch(err =>
                            toastr.error(
                                err.response.data.response_message,
                                err.response.data.response_title
                            )
                        );
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        });
    </script>
@endpush

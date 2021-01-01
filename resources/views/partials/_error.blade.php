@if (session()->has('error'))
    <div class="alert alert-danger text-center">{{ session()->get('error') }}</div>
@endif

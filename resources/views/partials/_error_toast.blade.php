@if (session()->has('error_toastr'))
    <script>
        toastr.error({{ session()->get('error_toastr') }}, i18n('messages.title.error'))
    </script>
@endif

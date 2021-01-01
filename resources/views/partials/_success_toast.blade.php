@if (session()->has('success_toastr'))
    <script>
        toastr.success({{ session()->get('success_toastr') }}, i18n('messages.title.success'))
    </script>
@endif

@push('script')
    @if (session()->has('success'))
        <script>
            $(document).Toasts('create', {
                class: 'bg-success',
                title: 'Success',
                subtitle: 'success',
                body: '{{ session('message') }}'
            });
            setTimeout(() => {
                $('#toastsContainerTopRight').remove();
            }, 3000);
        </script>
    @elseif(session()->has('error_msg'))
        <script>
            $(document).Toasts('create', {
                class: 'bg-danger',
                title: 'Error',
                body: 'Sorry your {{ session('message') }}'
            });
            setTimeout(() => {
                $('#toastsContainerTopRight').remove();
            }, 3000);
        </script>
    @endif
@endpush

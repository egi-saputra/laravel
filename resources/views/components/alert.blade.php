<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('alert'))
            Swal.fire({
                icon: '{{ session('alert.type') }}',   // success, error, warning, info, question
                title: '{{ session('alert.title') ?? ucfirst(session('alert.type')) }}',
                text: '{{ session('alert.message') }}',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6' // warna biru
            });
        @endif
    });
</script>

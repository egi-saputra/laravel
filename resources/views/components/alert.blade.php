<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if(session('alert'))
            Swal.fire({
    icon: '{{ session('alert.type') }}',
    title: '{{ session('alert.title') ?? ucfirst(session('alert.type')) }}',
    text: '{{ session('alert.message') }}',
    confirmButtonText: 'OK',
    confirmButtonColor: '#3085d6',
    width: '90%', // ukuran popup di layar kecil
    customClass: {
        popup: 'rounded-xl', // Tailwind works di properties lain
        title: 'text-lg font-semibold',
        content: 'text-sm text-gray-700'
    }
});

        @endif
    });
</script>

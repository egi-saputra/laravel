<!-- Load TinyMCE dari lokal -->
{{-- <script src="{{ asset('assets/tinymce/tinymce.min.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        tinymce.init({
            selector: 'textarea.tinymce', // semua textarea dengan class "tinymce"
            height: 400,
            menubar: false,
            plugins: 'code table lists link image media autolink preview',
            toolbar: [
                'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify',
                'bullist numlist outdent indent | link image media | code preview'
            ].join(' | '),
            branding: false,
            skin: 'oxide',
            content_style: 'body { font-family:Arial,sans-serif; font-size:14px }'
        });
    });
</script> --}}


<!-- TinyMCE -->
<script src="{{ asset('assets/tinymce/tinymce.min.js') }}"></script>

<script>
    function initTinyMCE() {
        if (typeof tinymce === 'undefined') return;

        // Hapus instance lama biar gak dobel
        tinymce.remove();

        // Inisialisasi ulang
        tinymce.init({
            selector: 'textarea.tinymce',
            height: 400,
            menubar: false,
            plugins: 'code table lists link image media autolink preview',
            toolbar: [
                'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify',
                'bullist numlist outdent indent | link image media | code preview'
            ].join(' | '),
            branding: false,
            skin: 'oxide',
            content_style: 'body { font-family:Arial,sans-serif; font-size:14px }'
        });
    }

    // Saat pertama kali load
    document.addEventListener("DOMContentLoaded", initTinyMCE);

    // Saat navigasi Turbo (pindah halaman via Turbo link)
    document.addEventListener("turbo:load", initTinyMCE);

    // Saat Turbo restore halaman dari cache (back/forward)
    document.addEventListener("turbo:render", initTinyMCE);

    // Bersihkan sebelum cache disimpan Turbo
    document.addEventListener("turbo:before-cache", function() {
        if (typeof tinymce !== 'undefined') tinymce.remove();
    });
</script>

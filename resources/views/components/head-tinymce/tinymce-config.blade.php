<!-- Load TinyMCE dari lokal -->
<script src="{{ asset('assets/tinymce/tinymce.min.js') }}"></script>

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
</script>

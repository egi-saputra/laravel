    {{-- TinyMce Versi 6 (Stable Version) --}}

    {{-- <script src="https://cdn.tiny.cloud/1/ansmv3zp2f8a48xqie4t4khjoutfqntk9w8v229bbzh7o0os/tinymce/6/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script> --}}

    {{-- <script src="https://cdn.tiny.cloud/1/NO_YOUR_API_KEY/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script> --}}

    {{-- TinyMce Versi 8 (Latest Version) --}}
    <script src="https://cdn.tiny.cloud/1/ansmv3zp2f8a48xqie4t4khjoutfqntk9w8v229bbzh7o0os/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

    <script>
        tinymce.init({
        selector: 'textarea#materi',
        plugins: 'code table lists',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table',
        height: 300
        });
    </script>

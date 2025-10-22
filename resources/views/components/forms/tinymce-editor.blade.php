{{-- @props([
    'id' => $attributes->get('id', $attributes->get('name', 'editor')),
    'name' => $attributes->get('name', 'editor'),
    'value' => $attributes->get('value', old($attributes->get('name', '')))
])

<textarea
    id="{{ $id }}"
    name="{{ $name }}"
    {{ $attributes->merge([
        'class' => 'tinymce-editor w-full rounded border border-gray-300 p-2 focus:ring focus:border-blue-400',
        'style' => 'min-height:200px;max-height:400px;overflow:auto;resize:vertical;',
    ]) }}
>{{ $value }}</textarea>

@once
    <script src="{{ asset('assets/tinymce/tinymce.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof tinymce !== 'undefined') {
                tinymce.remove(); // hapus instance sebelumnya biar gak dobel
                tinymce.init({
                    selector: 'textarea.tinymce-editor',
                    plugins: 'code table lists link image media fullscreen',
                    toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image media | code fullscreen',
                    height: 300,
                    menubar: false,
                    branding: false, // 🚫 hilangkan "Powered by Tiny"
                });
            }
        });
    </script>
@endonce --}}

<!-- Turbo Version 1 -->
@props([
    'id' => $attributes->get('id', $attributes->get('name', 'editor')),
    'name' => $attributes->get('name', 'editor'),
    'value' => $attributes->get('value', old($attributes->get('name', '')))
])

<textarea
    id="{{ $id }}"
    name="{{ $name }}"
    {{ $attributes->merge([
        'class' => 'tinymce-editor w-full rounded border border-gray-300 p-2 focus:ring focus:border-blue-400',
        'style' => 'min-height:200px;max-height:400px;overflow:auto;resize:vertical;',
    ]) }}
>{{ $value }}</textarea>

@once
    <script src="{{ asset('assets/tinymce/tinymce.min.js') }}"></script>
    <script>
        // Fungsi untuk hapus semua editor aktif (sebelum Turbo cache)
        function destroyTinyMCE() {
            if (typeof tinymce !== 'undefined') {
                tinymce.remove();
            }
        }

        // Fungsi untuk inisialisasi editor
        function initTinyMCE() {
            if (typeof tinymce === 'undefined') return;

            const editors = document.querySelectorAll('textarea.tinymce-editor');
            if (!editors.length) return;

            tinymce.init({
                selector: 'textarea.tinymce-editor',
                plugins: 'code table lists link image media fullscreen',
                toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image media | code fullscreen',
                height: 300,
                menubar: false,
                branding: false,
                setup: function (editor) {
                    editor.on('init', () => {
                        console.log(`TinyMCE ready: ${editor.id}`);
                    });
                }

                // Ini akan memaksa TinyMCE memuat CSS-nya dari path publik yang valid
                content_css: "{{ asset('assets/tinymce/skins/content/default/content.min.css') }}",
            });
        }

        // 🔥 Event Turbo Drive
        document.addEventListener('turbo:before-cache', destroyTinyMCE);
        document.addEventListener('turbo:load', initTinyMCE);

        // Untuk load awal (tanpa Turbo)
        document.addEventListener('DOMContentLoaded', initTinyMCE);
    </script>
@endonce

<!-- Turbo Version 2 -->
{{-- @props([
    'id' => $attributes->get('id', $attributes->get('name', 'editor')),
    'name' => $attributes->get('name', 'editor'),
    'value' => $attributes->get('value', old($attributes->get('name', '')))
])

<textarea
    id="{{ $id }}"
    name="{{ $name }}"
    {{ $attributes->merge([
        'class' => 'tinymce-editor w-full rounded border border-gray-300 p-2 focus:ring focus:border-blue-400',
        'style' => 'min-height:200px;max-height:400px;overflow:auto;resize:vertical;',
    ]) }}
>{{ $value }}</textarea>

@once
    <script src="{{ asset('assets/tinymce/tinymce.min.js') }}"></script>
    <script>
        // Fungsi inisialisasi TinyMCE
        function initTinyMCE() {
            if (typeof tinymce === 'undefined') return;

            // Hapus instance lama agar tidak dobel
            tinymce.remove();

            tinymce.init({
                selector: 'textarea.tinymce-editor',
                plugins: 'code table lists link image media fullscreen',
                toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image media | code fullscreen',
                height: 300,
                menubar: false,
                branding: false,
            });
        }

        // Jalankan TinyMCE saat pertama kali load
        document.addEventListener('DOMContentLoaded', initTinyMCE);

        // Jalankan ulang setelah navigasi Turbo
        document.addEventListener('turbo:load', initTinyMCE);

        // Bersihkan editor sebelum cache disimpan Turbo
        document.addEventListener('turbo:before-cache', function() {
            if (typeof tinymce !== 'undefined') tinymce.remove();
        });
    </script>
@endonce --}}

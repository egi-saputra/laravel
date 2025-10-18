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
@endonce


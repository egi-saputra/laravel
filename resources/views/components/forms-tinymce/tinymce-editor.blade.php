@props(['value' => ''])

<textarea
    id="{{ $attributes->get('id', 'materi') }}"
    name="{{ $attributes->get('name', 'materi') }}"
    {{ $attributes->merge(['class' => 'tinymce-editor overflow-x-auto md:overflow-x-visible whitespace-nowrap']) }}
>{{ old($attributes->get('name', 'materi'), $value) }}</textarea>

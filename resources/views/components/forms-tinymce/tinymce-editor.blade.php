{{-- @props(['value' => ''])

<textarea
    id="{{ $attributes->get('id', 'materi') }}"
    name="{{ $attributes->get('name', 'materi') }}"
    {{ $attributes->merge(['class' => 'tinymce-editor overflow-x-auto md:overflow-x-visible whitespace-nowrap']) }}
>{{ old($attributes->get('name', 'materi'), $value) }}</textarea> --}}

@props(['value' => '', 'name' => 'materi', 'id' => null])

<textarea
    id="{{ $id ?? Str::slug($name) }}"
    name="{{ $name }}"
    {{ $attributes->merge([
        'class' => 'tinymce overflow-x-auto md:overflow-x-visible whitespace-nowrap',
        'style' => 'min-height:200px;max-height:400px;overflow:auto;resize:vertical;'
    ]) }}
>{{ old($name, $value) }}</textarea>

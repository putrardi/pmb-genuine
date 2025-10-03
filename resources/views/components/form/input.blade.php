@props(['type' => 'text', 'name', 'value' => old($name)])
<input {{ $attributes->merge(['class' => 'input-lg']) }} type="{{ $type }}" name="{{ $name }}" value="{{ $value }}">

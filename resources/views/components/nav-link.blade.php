@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200'
            : 'inline-flex items-center text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-blue-100 hover:text-blue-600 transition-all duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

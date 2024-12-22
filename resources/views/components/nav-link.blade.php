@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center  px-3 py-5 border-b-2 border-indigo-400 text-md font-medium leading-5 text-gray-900 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center  border-b-2 px-3 py-5 rounded-md border-transparent text-md font-medium leading-5 text-white hover:bg-orange-300 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full px-4 py-2 border-l-4 border-purple-500 text-start text-base font-medium text-purple-600 focus:outline-none transition duration-150 ease-in-out'
            : 'block w-full px-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-slate-500 hover:text-slate-700 hover:bg-slate-50 focus:outline-none focus:text-slate-700 focus:bg-slate-50 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

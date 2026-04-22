@props(['disabled' => false, 'variant' => null])

@php
    $classes = 'w-full px-3 bg-white border border-slate-200 rounded-md text-black placeholder:text-slate-800 focus:bg-white focus:border-orange-500/40 focus:ring-4 focus:ring-orange-500/10 transition-all font-medium text-[14px] h-[40px]';

    if ($variant === 'filter') {
        $classes .= ' font-bold italic';
    }
@endphp

<input @disabled($disabled) {{ $attributes->merge(['class' => $classes]) }}>
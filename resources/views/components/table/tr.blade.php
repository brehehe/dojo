<tr {{ $attributes->merge(['class' => 'even:bg-slate-100 odd:bg-white hover:bg-slate-50 transition-colors group']) }}>
    {{ $slot }}
</tr>

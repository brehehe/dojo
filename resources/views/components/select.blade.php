@props(['placeholder' => 'Pilih opsi...', 'id' => null, 'variant' => null, 'options' => []])

<div 
    x-data="{ 
        value: @entangle($attributes->wire('model')),
        instance: null
    }"
    x-init="
        Alpine.nextTick(() => {
            let el = $( $refs.select );
            
            el.select2({
                placeholder: '{{ $placeholder }}',
                allowClear: true,
                width: '100%',
                dropdownParent: $(document.body)
            });

            // Set initial value from entangled state
            if (value) {
                el.val(value).trigger('change.select2');
            }

            el.on('change', (e) => {
                value = el.val();
            });

            $watch('value', (val) => {
                if (el.val() !== val) {
                    el.val(val).trigger('change.select2');
                }
            });
        });
    "
    x-on:destroy="$( $refs.select ).select2('destroy')"
    wire:ignore
    {{ $attributes->whereDoesntStartWith(['wire:model', 'options']) }}
    class="w-full relative group {{ $variant === 'filter' ? 'select2-filter-variant' : '' }}"
>
    <!-- Standard select for Select2 to hook into -->
    <select x-ref="select" class="form-control" style="width: 100%" {{ $attributes->has('multiple') ? 'multiple' : '' }}>
        <option value=""></option>
        @if($options)
            @foreach($options as $key => $value)
                <option value="{{ $key }}">{{ $value }}</option>
            @endforeach
        @endif
        {{ $slot }}
    </select>
</div>

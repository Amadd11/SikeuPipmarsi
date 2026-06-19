@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'w-full px-6 py-4 bg-zinc-50 border border-zinc-200 rounded-2xl
                    focus:outline-none focus:border-primary text-base transition-all duration-300
                    disabled:opacity-50 disabled:cursor-not-allowed',
]) !!} style="box-shadow: none;"
    onfocus="this.style.boxShadow='0 0 0 4px rgba(0,104,55,0.15)'" onblur="this.style.boxShadow='none'" />

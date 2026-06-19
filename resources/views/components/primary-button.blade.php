<button
    {{ $attributes->merge([
        'type' => 'submit',
        'class' => 'w-full py-4 bg-primary hover:bg-primary-dark text-white font-semibold
                            text-lg rounded-2xl transition-all active:scale-[0.97]
                            shadow-lg tracking-widest uppercase',
    ]) }}>
    {{ $slot }}
</button>

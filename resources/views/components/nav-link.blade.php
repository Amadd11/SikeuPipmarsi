@props(['href', 'icon', 'active' => false])

<a href="{{ $href }}"
    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
          {{ $active ? 'bg-primary text-white shadow-md' : 'text-gray-400 hover:bg-white/10 hover:text-white' }}">
    <span class="material-symbols-outlined text-[20px]">{{ $icon }}</span>
    {{ $slot }}
</a>

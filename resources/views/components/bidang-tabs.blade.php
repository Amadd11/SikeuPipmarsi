@props(['bidangList', 'activeBidang' => 'B1'])

<div class="flex flex-wrap gap-2 mb-6" x-data="{ activeTab: '{{ $activeBidang }}' }">
    @foreach($bidangList as $bidang)
        <button type="button"
                @click="activeTab = '{{ $bidang->kode }}'; $dispatch('tab-changed', '{{ $bidang->kode }}')"
                class="flex items-center gap-2 px-4 py-2 rounded-xl border transition-all duration-300"
                :class="activeTab === '{{ $bidang->kode }}' 
                        ? 'border-transparent text-white shadow-md' 
                        : 'border-border-light bg-white text-muted-text hover:bg-page-bg hover:text-navy opacity-75 grayscale-[50%]'"
                :style="activeTab === '{{ $bidang->kode }}' ? 'background-color: {{ $bidang->warna_hex ?? '#0e8a72' }}' : ''">
            <span class="w-2 h-2 rounded-full" 
                  :class="activeTab !== '{{ $bidang->kode }}' ? 'bg-current' : 'bg-white'"></span>
            <span class="text-sm font-bold">{{ $bidang->kode }}</span>
            <span class="text-xs max-w-[120px] truncate hidden md:inline-block" title="{{ $bidang->nama }}">{{ $bidang->nama }}</span>
        </button>
    @endforeach
</div>

@props(['paginator'])

@if ($paginator->hasPages())
    <div {{ $attributes->merge(['class' => 'px-5 py-3 border-t border-gray-100 text-xs bg-gray-50/30 flex items-center justify-between']) }}>
        <div class="hidden sm:block text-gray-500 font-medium">
            Menampilkan <span class="font-bold text-gray-800">{{ $paginator->firstItem() }}</span> hingga <span class="font-bold text-gray-800">{{ $paginator->lastItem() }}</span> dari <span class="font-bold text-gray-800">{{ $paginator->total() }}</span> data
        </div>
        <div class="flex-1 flex justify-center sm:justify-end">
            {{ $paginator->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>
    </div>
@endif

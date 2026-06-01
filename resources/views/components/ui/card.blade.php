<div {{ $attributes->merge(['class' => 'bg-white rounded-lg border border-gray-200 shadow-sm ' . $class]) }}>
    @if($title)
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
    </div>
    @endif
    <div class="{{ $padding ? 'px-6 py-4' : '' }}">
        {{ $slot }}
    </div>
</div>

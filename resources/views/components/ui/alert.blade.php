<div role="alert" {{ $attributes->merge(['class' => 'p-4 rounded-lg border ' .
    ($type === 'info' ? 'bg-blue-50 border-blue-200 text-blue-800 ' : '') .
    ($type === 'success' ? 'bg-green-50 border-green-200 text-green-800 ' : '') .
    ($type === 'warning' ? 'bg-yellow-50 border-yellow-200 text-yellow-800 ' : '') .
    ($type === 'error' ? 'bg-red-50 border-red-200 text-red-800 ' : '') .
    '']) }}>
    <div class="flex items-start">
        <div class="flex-1">{{ $slot }}</div>
        @if($dismissible)
        <button type="button" class="ml-3 text-current opacity-50 hover:opacity-100 focus:outline-none" onclick="this.parentElement.parentElement.remove()" aria-label="Cerrar">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
        @endif
    </div>
</div>

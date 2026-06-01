<button type="{{ $type }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 ' .
    ($color === 'blue' ? 'text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 ' : '') .
    ($color === 'red' ? 'text-white bg-red-600 hover:bg-red-700 focus:ring-red-500 ' : '') .
    ($color === 'green' ? 'text-white bg-green-600 hover:bg-green-700 focus:ring-green-500 ' : '') .
    ($color === 'yellow' ? 'text-white bg-yellow-600 hover:bg-yellow-700 focus:ring-yellow-500 ' : '') .
    ($color === 'gray' ? 'text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-gray-500 ' : '') .
    ($size === 'sm' ? 'px-3 py-1.5 text-sm ' : '') .
    ($size === 'md' ? 'px-4 py-2 text-sm ' : '') .
    ($size === 'lg' ? 'px-6 py-3 text-base ' : '') .
    'disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>

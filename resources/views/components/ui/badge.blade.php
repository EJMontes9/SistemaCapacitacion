<span {{ $attributes->merge(['class' => 'inline-flex items-center font-medium rounded-full ' .
    ($size === 'sm' ? 'px-2.5 py-0.5 text-xs ' : 'px-3 py-0.5 text-sm ') .
    ($color === 'gray' ? 'bg-gray-100 text-gray-800 ' : '') .
    ($color === 'blue' ? 'bg-blue-100 text-blue-800 ' : '') .
    ($color === 'red' ? 'bg-red-100 text-red-800 ' : '') .
    ($color === 'green' ? 'bg-green-100 text-green-800 ' : '') .
    ($color === 'yellow' ? 'bg-yellow-100 text-yellow-800 ' : '') .
    ($color === 'purple' ? 'bg-purple-100 text-purple-800 ' : '') .
    '']) }}>
    {{ $slot }}
</span>

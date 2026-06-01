<div class="{{ $responsive ? 'overflow-x-auto' : '' }}">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-200 ' .
        ($striped ? 'striped ' : '') .
        '']) }}>
    @if(count($headers) > 0)
        <thead class="bg-gray-50">
            <tr>
                @foreach($headers as $header)
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
    @endif
        <tbody class="bg-white divide-y divide-gray-200 {{ $hover ? 'hover-children' : '' }}">
            {{ $slot }}
        </tbody>
    </table>
</div>

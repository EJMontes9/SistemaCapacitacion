<tr>
    <td>
        @if ($file->type === 'image')
            <img src="{{ $file->thumbnail_url ?? $file->url }}"
                 alt="{{ $file->original_name }}"
                 class="img-thumbnail"
                 style="max-width: 80px; max-height: 60px;">
        @elseif ($file->type === 'video')
            <i class="fas fa-video fa-2x text-primary"></i>
        @elseif ($file->type === 'audio')
            <i class="fas fa-music fa-2x text-success"></i>
        @elseif ($file->type === 'document')
            <i class="fas fa-file-alt fa-2x text-warning"></i>
        @else
            <i class="fas fa-file fa-2x text-secondary"></i>
        @endif
    </td>
    <td class="text-truncate" style="max-width: 200px;" title="{{ $file->original_name }}">
        {{ $file->original_name }}
    </td>
    <td>
        @switch($file->type)
            @case('image')
                <span class="badge badge-primary">{{ $file->type }}</span>
                @break
            @case('video')
                <span class="badge badge-danger">{{ $file->type }}</span>
                @break
            @case('audio')
                <span class="badge badge-success">{{ $file->type }}</span>
                @break
            @case('document')
                <span class="badge badge-warning">{{ $file->type }}</span>
                @break
            @default
                <span class="badge badge-secondary">{{ $file->type }}</span>
        @endswitch
    </td>
    <td>{{ $file->size_formatted }}</td>
    <td>{{ $file->course?->title ?? 'General' }}</td>
    <td>{{ $file->downloads_count }}</td>
    <td>{{ $file->user?->name ?? 'N/A' }}</td>
    <td>{{ $file->created_at->format('d/m/Y') }}</td>
    <td class="d-flex">
        <a href="{{ route('admin.media.download', $file) }}"
           class="btn btn-sm btn-success mr-1"
           title="Descargar">
            <i class="fas fa-download"></i>
        </a>
        <a href="{{ route('admin.media.stream', $file) }}"
           class="btn btn-sm btn-info mr-1"
           title="Ver"
           target="_blank">
            <i class="fas fa-eye"></i>
        </a>
        <form action="{{ route('admin.media.destroy', $file) }}" method="POST" onsubmit="return confirm('¿Eliminar este archivo?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </td>
</tr>

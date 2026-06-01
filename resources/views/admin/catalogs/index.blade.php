@extends('adminlte::page')

@section('title', 'Catálogos')

@section('content_header')
    <h1 class="font-weight-bold">Catálogos</h1>
    <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Catálogos</li>
    </ol>
@stop

@section('content')
    @if (session('info'))
        <div class="alert alert-success">
            <strong>{{ session('info') }}</strong>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <a class="font-weight-bold" href="{{ route('admin.catalogs.create') }}">
                Crear nuevo catálogo <i class="fas fa-plus ml-1"></i>
            </a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Slug</th>
                        <th>Items</th>
                        <th>Modelo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($catalogs as $catalog)
                        <tr>
                            <td>{{ $catalog->id }}</td>
                            <td>{{ $catalog->name }}</td>
                            <td>{{ $catalog->slug }}</td>
                            <td><span class="badge badge-info">{{ $catalog->items_count }}</span></td>
                            <td>{{ $catalog->model_type ?? '—' }}</td>
                            <td>
                                <a href="{{ route('admin.catalogs.edit', $catalog) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('admin.catalogs.destroy', $catalog) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este catálogo?')">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay catálogos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        window.setTimeout(function () {
            $(".alert").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
    </script>
@stop

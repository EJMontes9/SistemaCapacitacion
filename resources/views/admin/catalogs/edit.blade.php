@extends('adminlte::page')

@section('title', 'Editar Catálogo')

@section('content_header')
    <h1 class="font-weight-bold">Editar Catálogo: {{ $catalog->name }}</h1>
@stop

@section('content')
    @if (session('info'))
        <div class="alert alert-success">
            <strong>{{ session('info') }}</strong>
        </div>
    @endif

    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Información del Catálogo</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.catalogs.update', $catalog) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                value="{{ old('name', $catalog->name) }}" required>
                            @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug"
                                value="{{ old('slug', $catalog->slug) }}" required>
                            @error('slug') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="2">{{ old('description', $catalog->description) }}</textarea>
                            @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="model_type">Modelo Asociado</label>
                            <input type="text" class="form-control @error('model_type') is-invalid @enderror" id="model_type" name="model_type"
                                value="{{ old('model_type', $catalog->model_type) }}">
                            @error('model_type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                            <a href="{{ route('admin.catalogs.index') }}" class="btn btn-secondary">Volver</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Agregar Elemento</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.catalogs.items.store', $catalog) }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="item_name">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="item_name" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="item_value">Valor</label>
                            <input type="text" class="form-control" id="item_value" name="value">
                        </div>

                        <div class="form-group">
                            <label for="item_code">Código</label>
                            <input type="text" class="form-control" id="item_code" name="code">
                        </div>

                        <div class="form-group">
                            <label for="item_description">Descripción</label>
                            <textarea class="form-control" id="item_description" name="description" rows="2"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="item_order">Orden</label>
                            <input type="number" class="form-control" id="item_order" name="order" value="0" min="0">
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="item_is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="item_is_active">Activo</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success btn-block">Agregar Elemento</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Elementos del Catálogo</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Nombre</th>
                                <th>Valor</th>
                                <th>Código</th>
                                <th>Activo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($catalog->items as $item)
                                <tr>
                                    <td>{{ $item->order }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->value ?? '—' }}</td>
                                    <td>{{ $item->code ?? '—' }}</td>
                                    <td>
                                        <span class="badge {{ $item->is_active ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $item->is_active ? 'Sí' : 'No' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                                data-target="#editItemModal{{ $item->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.catalogs.items.destroy', $item) }}" method="POST" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('¿Eliminar este elemento?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" role="dialog">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.catalogs.items.update', $item) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Editar: {{ $item->name }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="form-group">
                                                                <label>Nombre</label>
                                                                <input type="text" class="form-control" name="name" value="{{ $item->name }}" required>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Valor</label>
                                                                <input type="text" class="form-control" name="value" value="{{ $item->value }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Código</label>
                                                                <input type="text" class="form-control" name="code" value="{{ $item->code }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Descripción</label>
                                                                <textarea class="form-control" name="description" rows="2">{{ $item->description }}</textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Orden</label>
                                                                <input type="number" class="form-control" name="order" value="{{ $item->order }}" min="0">
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="edit_active_{{ $item->id }}" name="is_active" value="1" {{ $item->is_active ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="edit_active_{{ $item->id }}">Activo</label>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No hay elementos en este catálogo.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
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

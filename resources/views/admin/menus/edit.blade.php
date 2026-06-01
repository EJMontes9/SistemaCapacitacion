@extends('adminlte::page')

@section('title', 'Editar Menú')

@section('content_header')
    <h1 class="font-weight-bold">Editar Menú: {{ $menu->name }}</h1>
    <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.menus.index') }}">Menús</a></li>
        <li class="breadcrumb-item active">Editar: {{ $menu->name }}</li>
    </ol>
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
                    <h3 class="card-title">Información del Menú</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menus.update', $menu) }}" method="POST">
                        @csrf @method('PUT')

                        <div class="form-group">
                            <label for="name">Nombre</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                value="{{ old('name', $menu->name) }}" required>
                            @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug"
                                value="{{ old('slug', $menu->slug) }}" required>
                            @error('slug') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="2">{{ old('description', $menu->description) }}</textarea>
                            @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Actualizar</button>
                            <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Volver</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Agregar Elemento</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.menus.items.store', $menu) }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="item_title">Título <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="item_title" name="title" required>
                        </div>

                        <div class="form-group">
                            <label for="item_url">URL</label>
                            <input type="text" class="form-control" id="item_url" name="url" placeholder="/mi-ruta">
                        </div>

                        <div class="form-group">
                            <label for="item_route">Route</label>
                            <input type="text" class="form-control" id="item_route" name="route" placeholder="ruta.nombre">
                        </div>

                        <div class="form-group">
                            <label for="item_icon">Icono (clase CSS)</label>
                            <input type="text" class="form-control" id="item_icon" name="icon" placeholder="fas fa-home">
                        </div>

                        <div class="form-group">
                            <label for="item_parent_id">Elemento Padre</label>
                            <select class="form-control" id="item_parent_id" name="parent_id">
                                <option value="">— Ninguno —</option>
                                @foreach($parentItems as $pitem)
                                    <option value="{{ $pitem->id }}">{{ $pitem->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Roles (marcar los que pueden ver este elemento)</label>
                            <div class="row">
                                @foreach($roles as $roleName)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="role_{{ $loop->index }}" name="roles[]" value="{{ $roleName }}">
                                            <label class="form-check-label" for="role_{{ $loop->index }}">{{ $roleName }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <small class="form-text text-muted">Si no marcas ningún rol, el elemento será visible para todos los usuarios autenticados.</small>
                        </div>

                        <div class="form-group">
                            <label for="item_permission">Permiso (opcional)</label>
                            <input type="text" class="form-control" id="item_permission" name="permission" placeholder="admin.home">
                            <small class="form-text text-muted">
                                <strong>¿Qué hace?</strong> Restringe el acceso a usuarios que tengan un permiso específico de Spatie. 
                                Si se define, tiene prioridad sobre los roles. Ejemplo: <code>admin.home</code>, <code>instructor.home</code>.
                                Déjalo vacío si solo usas roles.
                            </small>
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
                    <h3 class="card-title">Elementos del Menú</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Orden</th>
                                <th>Título</th>
                                <th>URL/Route</th>
                                <th>Roles</th>
                                <th>Activo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menu->allItems as $item)
                                <tr>
                                    <td>{{ $item->order }}</td>
                                    <td>
                                        {{ $item->title }}
                                        @if($item->children->count() > 0)
                                            <span class="badge badge-info">{{ $item->children->count() }} hijos</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $item->route ? 'route: ' . $item->route : ($item->url ?: '—') }}</small>
                                    </td>
                                    <td>
                                        @if($item->roles)
                                            @foreach(explode(',', $item->roles) as $r)
                                                <span class="badge badge-primary">{{ trim($r) }}</span>
                                            @endforeach
                                        @elseif($item->permission)
                                            <span class="badge badge-warning">permiso: {{ $item->permission }}</span>
                                        @else
                                            <span class="badge badge-secondary">todos</span>
                                        @endif
                                    </td>
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
                                            <form action="{{ route('admin.menus.items.destroy', $item) }}" method="POST" style="display:inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('¿Eliminar este elemento?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" role="document">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.menus.items.update', $item) }}" method="POST">
                                                        @csrf @method('PUT')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Editar: {{ $item->title }}</h5>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Título</label>
                                                                        <input type="text" class="form-control" name="title" value="{{ $item->title }}" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Orden</label>
                                                                        <input type="number" class="form-control" name="order" value="{{ $item->order }}" min="0">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>URL</label>
                                                                        <input type="text" class="form-control" name="url" value="{{ $item->url }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group">
                                                                        <label>Route</label>
                                                                        <input type="text" class="form-control" name="route" value="{{ $item->route }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Icono</label>
                                                                <input type="text" class="form-control" name="icon" value="{{ $item->icon }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Roles</label>
                                                                <div class="row">
                                                                    @php $itemRoles = $item->roles ? explode(',', $item->roles) : []; @endphp
                                                                    @foreach($roles as $roleName)
                                                                        <div class="col-md-6">
                                                                            <div class="form-check">
                                                                                <input type="checkbox" class="form-check-input" id="edit_role_{{ $item->id }}_{{ $loop->index }}" name="roles[]" value="{{ $roleName }}"
                                                                                    {{ in_array($roleName, $itemRoles) ? 'checked' : '' }}>
                                                                                <label class="form-check-label" for="edit_role_{{ $item->id }}_{{ $loop->index }}">{{ $roleName }}</label>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                                <small class="form-text text-muted">Dejar vacío = visible para todos.</small>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Permiso</label>
                                                                <input type="text" class="form-control" name="permission" value="{{ $item->permission }}" placeholder="admin.home">
                                                                <small class="form-text text-muted">Prioritario sobre roles. Déjalo vacío si usas roles.</small>
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
                                @foreach($item->children as $child)
                                    <tr class="table-light">
                                        <td><small>{{ $child->order }}</small></td>
                                        <td><span class="ml-3">↳ {{ $child->title }}</span></td>
                                        <td><small>{{ $child->route ? 'route: ' . $child->route : ($child->url ?: '—') }}</small></td>
                                        <td>
                                            @if($child->roles)
                                                @foreach(explode(',', $child->roles) as $r)
                                                    <span class="badge badge-primary">{{ trim($r) }}</span>
                                                @endforeach
                                            @elseif($child->permission)
                                                <span class="badge badge-warning">permiso: {{ $child->permission }}</span>
                                            @else
                                                <span class="badge badge-secondary">todos</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $child->is_active ? 'badge-success' : 'badge-secondary' }}">
                                                {{ $child->is_active ? 'Sí' : 'No' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                                    data-target="#editItemModal{{ $child->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('admin.menus.items.destroy', $child) }}" method="POST" style="display:inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('¿Eliminar elemento hijo?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No hay elementos en este menú.</td>
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

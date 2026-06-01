@extends('adminlte::page')

@section('title', 'StudyApp')

@section('content_header')
    <div class="row ml-1">
        <h1>Lista de Usuarios</h1>
        <div class="ml-auto mr-1">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Crear Usuario</a>
            <a href="{{ route('admin.users.import.form') }}" class="btn btn-info">Importar CSV</a>
        </div>
    </div>
@stop

@section('content')

    @if (session('info'))
        <div class="alert alert-success">
            <strong>{{ session('info') }}</strong>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <form method="GET" action="{{ route('admin.users.index') }}" class="form-inline">
                <div class="form-group mr-2">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por nombre o email" value="{{ request('search') }}">
                </div>
                <div class="form-group mr-2">
                    <select name="role" class="form-control">
                        <option value="">Todos los roles</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-secondary">Filtrar</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-link">Limpiar</a>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th colspan="2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @forelse($user->roles as $role)
                                        <span class="badge badge-primary">{{ $role->name }}</span>
                                    @empty
                                        <span class="badge badge-secondary">Sin rol</span>
                                    @endforelse
                                </td>
                                <td width="10px">
                                    <a class="btn btn-info btn-sm" href="{{ route('admin.users.edit', $user) }}">Editar</a>
                                </td>
                                <td width="10px">
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No hay usuarios registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $users->links() }}
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 5000);
    </script>
@stop

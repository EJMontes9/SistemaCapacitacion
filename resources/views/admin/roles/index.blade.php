@extends('adminlte::page')

@section('title', 'StudyApp')

@section('content_header')
    <h1>Lista de Roles</h1>
@stop

@section('content')

    @if (session('info'))
        <div class="alert alert-success">
            <strong>{{ session('info') }}</strong>
        </div>
    @endif

    <div class="card">

        <div class="card-header">
            <a class="font-weight-bold" href="{{ route('admin.roles.create') }}">Crear nuevo Rol <i
                    class="fas fa-plus ml-1"></i> </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Rol</th>
                            <th>Permisos</th>
                            <th colspan="2"></th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td>
                                    @foreach ($role->permissions as $permission)
                                        <span class="badge badge-warning text-white">{{ $permission->name }}</span>
                                    @endforeach
                                </td>

                                <td width="10px">
                                    <a class="btn btn-info btn-sm" href="{{ route('admin.roles.edit', $role) }}">Editar</a>
                                </td>

                                <td width="10px">
                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No hay ningun rol registrado</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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

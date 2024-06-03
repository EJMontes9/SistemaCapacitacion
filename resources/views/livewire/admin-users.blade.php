<div>
    <div class="card">

        <!-- Buscador con botón-->
        <div class="card-header">
            <div class="row no-gutters">
                <div class="col-12 col-md-10">
                    <input wire:model.lazy="search" type="text" class="form-control"
                        placeholder="Ingrese un nombre o correo y presione enter...">
                </div>
                <div class="col-6 col-md-1 d-flex align-items-center justify-content-center">
                    <button wire:click="search" class="btn btn-success">Buscar</button>
                </div>
                <div class="col-6 col-md-1 d-flex align-items-center justify-content-end">
                    <button wire:click="clear" class="btn btn-danger">Limpiar</button>
                </div>
            </div>
        </div>
        <!-- Listado de usuarios -->

        @if ($users->count())

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Roles</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td width="10px">{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td width="200px">
                                        @foreach ($user->roles as $role)
                                            <span class="badge badge-warning text-white">{{ $role->name }}</span>
                                        @endforeach
                                    <td width="150px">
                                        <a class="btn btn-primary btn-sm"
                                            href="{{ route('admin.users.edit', $user) }}">Asignar Roles</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación con número de elementos-->
                <div class="card-footer d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <span>Mostrando {{ $users->firstItem() }} al {{ $users->lastItem() }} de {{ $users->total() }}
                        registros</span>
                    <form method="GET" action="{{ route('admin.users.index') }}" class="d-inline mt-2 mt-md-0">
                        <select name="perPage" onchange="this.form.submit()" class="form-control form-select">
                            @foreach ([5, 10, 15, 20] as $value)
                                <option value="{{ $value }}" @if ($value == $perPage) selected @endif>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </form>
                    <span class="mt-2 mt-md-0">{{ $users->links() }}</span>
                </div>
            @else
                <div class="card-body">
                    <strong>No hay registros...</strong>
                </div>
            </div>
        @endif
    </div>
</div>

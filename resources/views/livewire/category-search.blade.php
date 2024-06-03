<div>
    <div class="card-header">
        <div class="row no-gutters">
            <div class="col-12 col-md-10">
                <input wire:model.lazy="search" type="text" class="form-control"
                    placeholder="Buscar por nombre de categoría y presione enter">
            </div>
            <div class="col-6 col-md-1 d-flex align-items-center justify-content-end">
                <button wire:click="clear" class="btn btn-danger">Limpiar</button>
            </div>
        </div>
    </div>

    @if ($categories->count())
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Categoría</th>
                            <th>Fecha de creación</th>
                            <th>Fecha de Modificación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($category->created_at)->locale('es')->isoFormat('LL') }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($category->updated_at)->locale('es')->isoFormat('LL') }}
                                </td>
                                <td>
                                    <a class="btn btn-info btn-sm"
                                        href="{{ route('admin.categories.edit', $category) }}">Editar</a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                        style="display: inline;"
                                        onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta categoría?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer d-flex flex-column flex-md-row justify-content-between align-items-center">
                <span>Mostrando {{ $categories->firstItem() }} al {{ $categories->lastItem() }} de
                    {{ $categories->total() }} registros</span>
                <form method="GET" action="{{ route('admin.categories.index') }}" class="d-inline mt-2 mt-md-0">
                    <select name="perPage" onchange="this.form.submit()" class="form-control form-select">
                        @foreach ([5, 10, 15, 20] as $perPage)
                            <option value="{{ $perPage }}" @if ($perPage == $this->perPage) selected @endif>
                                {{ $perPage }}</option>
                        @endforeach
                    </select>
                </form>
                <span class="mt-2 mt-md-0">
                    {{ $categories->links() }}
                </span>
            </div>
        </div>
    @else
        <div class="card-body">
            <strong>No hay registros...</strong>
        </div>
    @endif
</div>

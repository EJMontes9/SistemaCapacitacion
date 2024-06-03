@extends('adminlte::page')

@section('title', 'StudyApp')

@section('content_header')
    <div class="row ml-1">
        <h1 class="font-weight-bold">Editar categoría</h1>
        <a class="btn btn-success ml-auto mr-1" href="{{ route('admin.categories.index') }}">Regresar</a>
    </div>
@stop

@section('content')
    <div class="card mt-4">
        <div class="card-body">

            <!-- Formulario para editar categoría -->
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4 row">
                    <label class="text-lg text-gray-600 col-sm-3">Nombre de la categoría <span class="text-red-500">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="name" id="name"
                            value="{{ old('name', $category->name) }}" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-secondary">Actualizar Categoría</button>

                    <button type="button" onclick="window.location='{{ route('admin.categories.index') }}'"
                        class="btn btn-danger">Cancelar</button>
                </div>
            </form>
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

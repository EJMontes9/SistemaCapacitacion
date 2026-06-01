@extends('adminlte::page')

@section('title', 'Crear Catálogo')

@section('content_header')
    <h1 class="font-weight-bold">Crear Nuevo Catálogo</h1>
@stop

@section('content')
    <div class="card mt-4">
        <div class="card-body">
            <form action="{{ route('admin.catalogs.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="name">Nombre del Catálogo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                        value="{{ old('name') }}" required>
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="slug">Slug <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug"
                        value="{{ old('slug') }}" required>
                    @error('slug') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    <small class="form-text text-muted">Identificador único (ej: modalidades, periodos).</small>
                </div>

                <div class="form-group">
                    <label for="description">Descripción</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                        rows="3">{{ old('description') }}</textarea>
                    @error('description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="model_type">Modelo Asociado</label>
                    <input type="text" class="form-control @error('model_type') is-invalid @enderror" id="model_type" name="model_type"
                        value="{{ old('model_type') }}" placeholder="App\Models\Course">
                    @error('model_type') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    <small class="form-text text-muted">Opcional: nombre de la clase del modelo relacionado.</small>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Crear Catálogo</button>
                    <button type="button" onclick="window.location='{{ route('admin.catalogs.index') }}'" class="btn btn-danger">Cancelar</button>
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
        $('#name').on('blur', function () {
            if (!$('#slug').val()) {
                $('#slug').val(
                    $(this).val().toLowerCase()
                        .replace(/[^a-z0-9]+/g, '-')
                        .replace(/^-+|-+$/g, '')
                );
            }
        });
    </script>
@stop

@extends('adminlte::page')

@section('title', 'StudyApp')

@section('content_header')
    <div class="row ml-1">
        <h1>Importar Usuarios desde CSV</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-success ml-auto mr-1">Regresar</a>
    </div>
@stop

@section('content')

    @if (session('info'))
        <div class="alert alert-success">
            <strong>{{ session('info') }}</strong>
        </div>
    @endif

    @if (session('errors'))
        <div class="alert alert-warning">
            <ul>
                @foreach (session('errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Formato del CSV:</strong> El archivo debe tener las columnas: <code>name, email, password, role</code>
                <br>La primera fila debe ser el encabezado.
                <br>La columna <code>password</code> y <code>role</code> son opcionales. Si no se especifica password, se usará <code>password123</code>.
                <br>Ejemplo:
                <pre>name,email,password,role
Juan Pérez,juan@ejemplo.com,secreto123,Alumno
María López,maria@ejemplo.com,,Instructor</pre>
            </div>

            {!! Form::open(['route' => 'admin.users.import', 'method' => 'post', 'files' => true]) !!}

            <div class="form-group">
                <label for="csv_file">Seleccionar archivo CSV</label>
                {!! Form::file('csv_file', ['class' => 'form-control-file', 'accept' => '.csv,.txt', 'required']) !!}
                @error('csv_file') <small class="text-danger">{{ $message }}</small> @enderror
            </div>

            {!! Form::submit('Importar', ['class' => 'btn btn-primary mt-2']) !!}

            {!! Form::close() !!}
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

@extends('adminlte::page')

@section('content_header')
    <h1>Lista Categorías</h1>
@stop

@section('content')

@if (session('info'))
    <div class="alert alert-success">
        <strong>{{ session('info') }}</strong>
    </div>
@endif

<div class="card">

    <div class="card-header">
        <a class="font-weight-bold" href="{{ route('admin.categories.create') }}">Crear nueva categoría <i class="fas fa-plus ml-1"></i> </a>
    </div>

    @livewire('category-search')

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

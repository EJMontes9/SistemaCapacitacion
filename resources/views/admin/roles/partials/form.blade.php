<div class="form-group">
    {!! Form::label('name', 'Nombre') !!}
    {!! Form::text('name', null, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''), 'placeholder' => 'Ingrese el nombre del rol']) !!}
    
    @error('name')
    <span class="invalid-feedback">
        <strong>Este campo es requerido</strong>
    </span>
    @enderror
</div>

    <strong>Permisos</strong>
    <br>

    @foreach ($permissions as $permission)
        <div>
            <label>
                {!! Form::checkbox('permissions[]', $permission->id, null, ['class' => 'mr-1']) !!}
                {{ $permission->name }}
            </label>
        </div>
    @endforeach

    @error('permissions')
    <span>
        <small class="text-danger">Seleccione al menos una de las opciones</small>
    </span>
    <br>    
    @enderror
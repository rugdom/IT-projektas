@extends('layouts.app')


@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Redaguoti naudotojo duomenis:</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('users.index') }}"> Atgal</a>
        </div>
    </div>
</div>


@if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Ups!</strong> Yra problemų su jūsų įvesta informacija.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif



{!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Vardas:</strong>
            {!! Form::text('vardas', $user->name, array('placeholder' => 'Vardas','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>El. paštas:</strong>
            {!! Form::text('el_paštas', $user->email, array('placeholder' => 'El. paštas','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Slaptažodis:</strong>
            {!! Form::password('slaptažodis', array('placeholder' => 'Slaptažodis','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Patvirtinti slaptažodį:</strong>
            {!! Form::password('patvirtinti_slaptažodį-password', array('placeholder' => 'Patvirtinti slaptažodį','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Rolė:</strong>
            {!! Form::select('rolė[]', $roles,$userRole, array('class' => 'form-control','multiple')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 text-center">
        <button type="submit" class="btn btn-primary">Redaguoti</button>
    </div>
</div>
{!! Form::close() !!}

@endsection

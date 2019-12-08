@extends('layouts.app')

@section('content')
    <div class="container">
        <h4>Pridėti naują tipą:</h4>
        <br/>
        <div style="width: 500px; margin: 0px auto">

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

        {!! Form::open(array('route' => 'events.addType','method'=>'POST')) !!}
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Pavadinimas:</strong>
                    {!! Form::text('pavadinimas', null, array('placeholder' => 'Pavadinimas','class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Tėvinis tipas:</strong>
                    <select class="form-control" name="tėvinis_tipas">
                        <option value="">---------</option>
                        @foreach($types as $type)
                            <option value="{{$type->id}}">{{$type->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-primary">Pridėti</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    </div>
@endsection

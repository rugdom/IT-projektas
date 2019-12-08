@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Užsisakykite informaciją apie naujausius renginius</h4>
    <br/>
    <div style="width: 500px; margin: 0px auto">

        @if (\Session::has('error'))
            <div class="alert alert-danger">
                <p>{!! \Session::get('error') !!}</p>
            </div>
        @endif
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <p>{!! \Session::get('success') !!}</p>
            </div>
        @endif
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

        <h5><strong>Galite pasirinkti, apie kokius naujus renginius norite būti informuoti:</strong></h5>

        {!! Form::open(array('route' => 'events.subscribe','method'=>'POST')) !!}
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Amžiaus grupė:</strong>
                    <br/>
                    @foreach($ageGroups as $ageGroup)
                        <label style="font-weight: normal">
                            {{ Form::checkbox('amžiaus_grupė[]', $ageGroup->id, false, array('class' => 'name')) }}
                            {{ $ageGroup->name }}
                        </label>
                        <br/>
                    @endforeach
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Vieta:</strong>
                    {!! Form::text('vieta', null, array('placeholder' => 'Regionas','class' => 'form-control')) !!}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <table id="tree-table" class="table table-hover table-bordered">
                        <tbody>
                        <th>Tipai </th>
                        @foreach($parentTypes as $type)
                            <tr data-id="{{$type->id}}" data-parent="0" data-level="1">
                                <td data-column="name">
                                    <label style="font-weight: normal">
                                        {{ $type->name }}
                                        {!! Form::checkbox('tipas[]', $type->id, false, array('class'=>'name')) !!}
                                    </label>
                                </td>
                            </tr>
                            @if(count($type->subtype))
                                @include('events.subTypeList',['subtypes' => $type->subtype, 'dataParent' => $type->id , 'dataLevel' => 1])
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Kiek laiko norite gauti pranešimus:</strong>
                    <select class="form-control" name="laiko_periodas">
                        <option value="">-------</option>
                        <option value="1">Dieną</option>
                        <option value="7">Savaitę</option>
                        <option value="30">Mėnesį</option>
                        <option value="365">Metus</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Prieš kiek laiko pranešti:</strong>
                    <select class="form-control" name="prieš_kiek_laiko_pranešti">
                        <option value="">-------</option>
                        <option value="1">Prieš dieną</option>
                        <option value="7">Prieš savaitę</option>
                        <option value="30">Prieš mėnesį</option>
                        <option value="365">Prieš metus</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <label style="font-weight: normal">
                        {!! Form::checkbox('nemokama', null, false, array('class' => 'name')) !!}
                    </label>
                    <strong>Domina tik nemokami renginiai</strong>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <button type="submit" class="btn btn-primary">Siųsti</button>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection

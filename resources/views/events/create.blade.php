@extends('layouts.app')

@section('content')
    <div class="container">
        <h4>Įkelti naują renginį</h4>
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

            <h5><strong>Užpildykite duomenis apie renginį:</strong></h5>

            {!! Form::open(array('route' => 'events.store','method'=>'POST')) !!}
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <a style="margin-bottom: 10px" href="{{ route('events.createType') }}" class="btn btn-primary">Pridėti tipą</a>
                    <div class="form-group">
                        <table id="tree-table" class="table table-hover table-bordered">
                            <tbody>
                            <th>Tipai </th>
                            @foreach($parentTypes as $type)
                                <tr data-id="{{$type->id}}" data-parent="0" data-level="1">
                                    <td data-column="name">
                                        <label style="font-weight: normal">
                                            {{ $type->name }}
                                            @if (Auth::user()->specialization == $type->name)
                                                {!! Form::checkbox('tipas[]', $type->id, true, array('class'=>'name')) !!}
                                            @else
                                                {!! Form::checkbox('tipas[]', $type->id, false, array('class'=>'name')) !!}
                                            @endif
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
                        <strong>Pavadinimas:</strong>
                        {!! Form::text('pavadinimas', null, array('placeholder' => 'Pavadinimas','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Data:</strong>
                        {!! Form::date('data', null, array('placeholder' => 'Data','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Laikas:</strong>
                        {!! Form::time('laikas', null, array('placeholder' => 'Laikas','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Paveikslėlio nuoroda:</strong>
                        {!! Form::text('paveikslėlis', null, array('placeholder' => 'Paveikslėlio nuoroda','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Regionas:</strong>
                        {!! Form::text('regionas', null, array('placeholder' => 'Regionas','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Adresas:</strong>
                        {!! Form::text('adresas', null, array('placeholder' => 'Adresas','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Ar mokamas renginys:</strong><br/>
                        <label style="font-weight: normal">
                        {!! Form::checkbox('nemokama', null, false, array('class' => 'name')) !!}
                            Nemokamas </label>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Jei renginys mokamas, nuoroda, kur nusipirkti bilietą:</strong>
                        {!! Form::text('nuoroda_pirkti', null, array('placeholder' => 'Nuoroda pirkti','class' => 'form-control')) !!}
                    </div>
                </div>
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
                        <strong>Pasirinkite tokio pat tipo renginį:</strong>
                        <select class="form-control" name="renginio_nuoroda">
                            <option value="">-----------</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}">{{ $event->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Raktiniai žodžiai:</strong>
                        {!! Form::text('raktinis_zodis', null, array('placeholder' => '#raktinis-žodis, ...','class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-primary">Sukurti</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

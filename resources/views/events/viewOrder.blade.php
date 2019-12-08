@extends('layouts.app')

@section('content')
    <div class="container">
        <h4>Jūsų užsakymo informacija</h4>
        <br/>
        <div style="width: 500px; margin: 0px auto">
            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <p>{!! \Session::get('success') !!}</p>
                </div>
            @endif
                @if (\Session::has('error'))
                    <div class="alert alert-danger">
                        <p>{!! \Session::get('error') !!}</p>
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

                <a href="{{ route('events.orderEvents') }}" class="btn btn-primary">Pežiūrėti siūlytus renginius</a>
            <h5><strong>Galite keisti užsakymo informaciją:</strong></h5>

            {!! Form::open(array('route' => 'events.editOrder','method'=>'POST')) !!}
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Amžiaus grupė:</strong>
                        <br/>
                        @foreach($ageGroups as $ageGroup)
                            <label style="font-weight: normal">
                                @if (in_array($ageGroup->id, $selectedAgeGroups))
                                    {{ Form::checkbox('amžiaus_grupė[]', $ageGroup->id, true, array('class' => 'name')) }}
                                @else
                                    {{ Form::checkbox('amžiaus_grupė[]', $ageGroup->id, false, array('class' => 'name')) }}
                                @endif
                                {{ $ageGroup->name }}
                            </label>
                            <br/>
                        @endforeach
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Vieta:</strong>
                        @if($events[0]->region == null)
                            {!! Form::text('vieta', null, array('placeholder' => 'Regionas','class' => 'form-control')) !!}
                        @else
                            {!! Form::text('vieta', $events[0]->region, array('placeholder' => 'Regionas','class' => 'form-control')) !!}
                        @endif
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
                                            @if (in_array($type->id, $selectedTypes))
                                                {!! Form::checkbox('tipas[]', $type->id, true, array('class'=>'name')) !!}
                                            @else
                                                {!! Form::checkbox('tipas[]', $type->id, false, array('class'=>'name')) !!}
                                            @endif
                                        </label>
                                    </td>
                                </tr>
                                @if(count($type->subtype))
                                    @include('events.orderSubTypeList',['subtypes' => $type->subtype, 'dataParent' => $type->id , 'dataLevel' => 1, 'selectedTypes'=>$selectedTypes])
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
                            @if($events[0]->period == "1")<option value="1" selected>Dieną</option> @else <option value="1" >Dieną</option> @endif
                            @if($events[0]->period == "7")<option value="7" selected>Savaitę</option> @else <option value="7" >Savaitę</option> @endif
                            @if($events[0]->period == "30")<option value="30" selected>Mėnesį</option>@else <option value="30">Mėnesį</option> @endif
                            @if($events[0]->period == "365")<option value="365" selected>Metus</option>@else <option value="365">Metus</option> @endif
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Prieš kiek laiko pranešti:</strong>
                        <select class="form-control" name="prieš_kiek_laiko_pranešti">
                            <option value="">-------</option>
                            @if($events[0]->inform_before == "1")<option value="1" selected>Prieš dieną</option>@else <option value="1">Prieš dieną</option> @endif
                            @if($events[0]->inform_before == "7")<option value="7" selected>Prieš savaitę</option>@else <option value="7" >Prieš savaitę</option>@endif
                            @if($events[0]->inform_before == "30")<option value="30" selected>Prieš mėnesį</option>@else <option value="30" selected>Prieš mėnesį</option>@endif
                            @if($events[0]->inform_before == "365")<option value="365" selected>Prieš metus</option>@else <option value="365" >Prieš metus</option> @endif
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label style="font-weight: normal">
                            @if($events[0]->only_free == 1)
                                {!! Form::checkbox('nemokama', null, true, array('class' => 'name')) !!}
                            @else
                                {!! Form::checkbox('nemokama', null, true, array('class' => 'name')) !!}
                            @endif
                        </label>
                        <strong>Domina tik nemokami renginiai</strong>
                    </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-primary">Atnaujinti</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

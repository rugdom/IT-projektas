@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Jums siūlyti renginiai:</h3>
        <br/>
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <p>{!! \Session::get('success') !!}</p>
            </div>
        @endif
        {!! Form::open(array('route' => 'events.saveOrders','method'=>'POST')) !!}
            {{ csrf_field() }}
            <table class="table table-bordered">
                <tr>
                    <th>Nr</th>
                    <th>Pavadinimas</th>
                    <th>Data ir laikas</th>
                    <th>Adresas</th>
                    <th>Regionas</th>
                    <th width="280px">Dalyvavimas</th>
                </tr>
                @foreach ($orders as $order)
                    <tr>
                        {!! Form::hidden('orders[]', $order->id, array()) !!}
                        <td>{{ ++$i }}</td>
                        <td>{{ $order['event']->name }}</td>
                        <td>{{ $order['event']->date }} {{ $order['event']->time }}</td>
                        <td>{{ $order['event']->address }}</td>
                        <td>{{ $order['event']->region }}</td>
                        <td>
                            <label for="dalyvavimas">Dalyvavau</label>
                            @if ($order->participation == 1)
                                {{ Form::checkbox('participation[]', $order->id, true, array('class' => 'name')) }}
                            @else
                                {{ Form::checkbox('participation[]', $order->id, false, array('class' => 'name')) }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
            <button type="submit" class="btn btn-primary">Išsaugoti</button>
        {!! Form::close() !!}
    </div>
@endsection

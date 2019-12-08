@extends('layouts.app')

@section('content')
    <div class="container">
       <h3>{{ $event->name }}</h3>
        <img style="height: 500px" src="{{ $event->image }}">
        <p>Data ir laikas: {{ $event->date }} {{ $event->time }}</p>
        <p>Adresas: {{ $event->address }}, {{ $event->region }}</p>
        @if (count($sameTypeEvents) != 0)
            <p>Nuorodos į tokio paties tipo renginius: </p>
            <ul>
                @foreach($sameTypeEvents as $sameTypeEvent)
                    <li><a href="/renginiai/id={{ $sameTypeEvent->id }}">{{ $sameTypeEvent->name }}</a></li>
                @endforeach
            </ul>
        @endif
        <p>Raktiniai žodžiai:
            @foreach($eventWithKeys as $key)
                @if($key->fk_event_keyword == $event->id)
                    #{{ $key->word }}
                @endif
            @endforeach
        </p>
    </div>
@endsection

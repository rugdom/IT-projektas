@extends('layouts.app')

@section('content')
<div class="container">
    <row>
        <h5>Populiariausi raktiniai žodžiai:</h5>
        <ul id="key-words">
            @foreach($keywords as $key)
                <li><a href="{{ route('events.eventsByKey', $key->word) }}">#{{ $key->word }}</a></li>
            @endforeach
        </ul>
        <br/>
    </row>
    <form class="form-row" method="POST" action="{{ route('events.filter') }}">
        {{ csrf_field() }}
        <div class="form-group col-md-3">
            <label for="regionas">Regionas</label>
            <select class="form-control" name="regionas">
                <option value="">-----------</option>
                @foreach($regions as $region)
                    <option value="{{$region}}">{{$region}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="nuo-kada">Nuo kada:</label>
            <input type="date" class="form-control" name="nuo-kada">
        </div>
        <div class="form-group col-md-3">
            <label for="iki-kada">Iki kada:</label>
            <input type="date" class="form-control" name="iki-kada">
        </div>
        <div class="form-group col-md-3">
            <button type="submit" class="btn btn-primary"
                    style="width: 100px; margin-left: 30px; margin-top: 25px">Atrinkti</button>
        </div>
        </form>
          <br/>
      <div class="container">

          @foreach($events as $event)
              <div class="col-md-3"><h3>{{ $event->name }}</h3>
                  <a href="/renginiai/id={{ $event->id }}"><img style="height: 350px" src="{{ $event->image }}"></a>
                  <p>Data ir laikas: {{ $event->date }} {{ $event->time }}</p>
                  <p>Raktiniai žodžiai:
                      @foreach($eventsWithKeys as $eventWithKeys)
                          @if($eventWithKeys->fk_event_keyword == $event->id)
                            #{{ $eventWithKeys->word }}
                          @endif
                      @endforeach
                  </p>
              </div>
          @endforeach

      </div>
</div>
@endsection

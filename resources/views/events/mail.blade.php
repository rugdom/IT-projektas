<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <div>
        <div style="text-align: center; background: #92bce0; height: 100px">
            <h2 style="color:white; padding: 40px 0">Jums siūlomi nauji renginiai:</h2>
        </div>
        @foreach($data as $event)
            <div style="margin-left: 10px">
                <h3>{{ $event['event']->name }}</h3>
                <img style="height: 250px" src="{{ $event['event']->image }}">
                <p>Renginio laikas: {{ $event['event']->date }} {{ $event['event']->time }}</p>
                <p>Renginio adresas: {{ $event['event']->address }}</p>
                <br/>
            </div>
        @endforeach
    </div>
</body>
</html>

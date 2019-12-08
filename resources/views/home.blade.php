@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center">
        @if (session('status'))
             <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        
        Jūs prisijungėte kaip 
        @if(!empty(Auth::user()->getRoleNames()))
                @foreach(Auth::user()->getRoleNames() as $v)
                    {{ $v }}
                @endforeach
        @endif
    </h1>
</div>
@endsection

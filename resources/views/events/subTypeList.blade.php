@foreach($subtypes as $type)
    <tr data-id="{{$type->id}}" data-parent="{{$dataParent}}" data-level = "{{$dataLevel + 1}}">
        <td data-column="name" style="padding-left: {{ $dataLevel * 30 }}px">
            <label style="font-weight: normal;">{{ $type->name }}
                @if (Auth::user()->specialization == $type->name)
                    {!! Form::checkbox('tipas[]', $type->id, true, array('class'=>'name')) !!}
                @else
                    {!! Form::checkbox('tipas[]', $type->id, false, array('class'=>'name')) !!}
                @endif
            </label>
        </td>
    </tr>
    @if(count($type->subtype))
        @include('events.subTypeList',['subtypes' => $type->subtype, 'dataParent' => $type->id, 'dataLevel' => $dataLevel + 1])
    @endif
@endforeach


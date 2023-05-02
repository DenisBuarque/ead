<option value=""></option>
@if ($disciplines->count() > 0)    
    @foreach ($disciplines as $value)
        <option value="{{$value->id}}">{{$value->title}}  ({{ $value->institution }})</option>
    @endforeach
@else
    <option value="">Disciplinas disponíveis</option>
@endif

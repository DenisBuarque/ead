
@foreach ($subjects as $item)
    <option value="{{$item->id}}">{{$item->title}}</option>
@endforeach

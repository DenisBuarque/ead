@if (count($disciplines) > 0)
    @foreach ($disciplines as $item)
        <option value="{{ $item->id }}">{{ $item->title }} ({{ $item->institution }})</option>
    @endforeach
@else
    <option>Não há disciplinas ativas para esse curso.</option>
@endif

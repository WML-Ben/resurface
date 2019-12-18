<button type="button" id="{{ $params['button-id'] ?? '' }}" class="button {{ $params['button-class'] ?? 'btn-primary' }}"
@if (!empty($params['attributes']))
    @foreach ($params['attributes'] as $key => $value)
        {!! ' ' . $key .'='. str_replace(' ', '&nbsp;', $value) !!}
    @endforeach
@endif
>{!!  $buttonLabel !!}</button>
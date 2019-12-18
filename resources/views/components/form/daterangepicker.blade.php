{{-- 2017-08-11--}}
@if (!empty($params['label']))
    <span class="form-field-label">{{ $params['label'] }}{!! !empty($params['hint']) ? ' <span class="hint">'.$params['hint'].'</span>' : '' !!}:{!! !empty($params['required']) ? '<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="'. (!empty($params['title']) ? $params['title'] : 'this field is required') .'"></i>' : '' !!}</span>
@endif
<label for="{{ !empty($params['id']) ? $params['id'] : $from .'_'. $to }}" class="field prepend-icon">
    <input type="text"
           class="{{ 'gui-input bootstrap-daterangepicker bootstrap-daterange-picker'. (!empty($params['language']) && $params['language'] == 'es' ? '-es' : '') . (!empty($params['class']) ? ' '.$params['class'] : '') }}"
           name="{{ !empty($params['id']) ? $params['id'] : $from .'_'. $to }}"
           id="{{ !empty($params['id']) ? $params['id'] : $from .'_'. $to }}"
           placeholder="{{ !empty($params['placeholder']) ? $params['placeholder'] : '' }}"
    @if (!empty($params['attributes']))
        @foreach ($params['attributes'] as $key => $value)
            {{ ' ' . $key .'="'. $value .'"' }}
                @endforeach
            @endif
    >
    <input type="hidden" name="{{ $from }}" id="{{ $from }}" value="">
    <input type="hidden" name="{{ $to }}" id="{{ $to }}" value="">
    <span class="field-icon"><i class="fa fa-calendar-o"></i></span>
</label>

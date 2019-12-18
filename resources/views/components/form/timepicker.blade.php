{{-- 2018-03-01--}}
<?php
if (!empty($params['value'])) {
    $paramsValue = $params['value'];

    if (! $paramsValue instanceof \Carbon\Carbon) {
        $paramsValue = \Carbon\Carbon::createFromFormat('H:i:s', $paramsValue);
    }
    $paramsValue = $paramsValue->format('g:i A');
} else {
    $paramsValue = '';
}
?>
<span class="form-field-label">{{ !empty($params['label']) ? $params['label'] : ucfirst($name) }}{!! !empty($params['hint']) ? ' <span class="hint">'.$params['hint'].'</span>' : '' !!}:{!! !empty($params['required']) ? '<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="'. (!empty($params['title']) ? $params['title'] : 'this field is required') .'"></i>' : '' !!}</span>
<label for="{{ !empty($params['id']) ? $params['id'] : $name }}" class="field prepend-icon">
    <input type="text"
           value="{{ $paramsValue }}"
           class="{{ 'gui-input bootstrap-time-picker'. (!empty($params['language']) && $params['language'] == 'es' ? '-es' : '') . (!empty($params['class']) ? ' '.$params['class'] : '') }}"
           id="{{ !empty($params['id']) ? $params['id'] : $name }}"
           name="{{ $name }}"
           placeholder="{{ !empty($params['placeholder']) ? $params['placeholder'] : '' }}"
    @if (!empty($params['attributes']))
        @foreach ($params['attributes'] as $key => $value)
            {{ ' ' . $key .'="'. $value .'"' }}
        @endforeach
    @endif
    >
    <span class="field-icon"><i class="fa fa-clock-o"></i></span>
</label>
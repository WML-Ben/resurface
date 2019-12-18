@if (!empty($params['maxChars']))
    <div class="char-counter" data-max="{{ $params['maxChars'] }}">
        <span class="counter-container">Left: <span class="counter"></span></span>
@endif
<span class="form-field-label">{{ !empty($params['label']) ? $params['label'] : ucfirst($name) }}{!! !empty($params['hint']) ? ' <span class="hint">'.$params['hint'].'</span>' : '' !!}:{!! !empty($params['required']) ? '<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="'. (!empty($params['title']) ? $params['title'] : 'this field is required') .'"></i>' : '' !!}</span>
<label for="{{ !empty($params['id']) ? $params['id'] : $name }}" class="field prepend-icon">
    {!! Form::textarea($name, !empty($params['value']) ? $params['value'] : null, array_merge(['class' => 'gui-textarea'.(!empty($params['class']) ? ' '.$params['class'] : ''), 'id' => !empty($params['id']) ? $params['id'] : $name, 'placeholder' => !empty($params['placeholder']) ? $params['placeholder'] : ''], !empty($params['attributes']) ? $params['attributes'] : [])) !!}
    <span class="field-icon"><i class="{{ !empty($params['iconClass']) ? $params['iconClass'] : 'fa fa-file-text-o' }}"></i></span>
</label>
@if (!empty($params['maxChars']))
    </div>
@endif
<div class="mce-wrapper {{ $lang }}">
    <span class="form-field-label">{{ !empty($params['label']) ? $params['label'] : ucfirst($name) }}{!! !empty($params['hint']) ? ' <span class="hint">'.$params['hint'].'</span>' : '' !!}:{!! !empty($params['required']) ? '<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="'. (!empty($params['title']) ? $params['title'] : 'this field is required') .'"></i>' : '' !!}</span>
    <textarea name="{{ $name }}" id="{{ !empty($params['id']) ? $params['id'] : $name }}"{!! isset($params['required']) ? ' data-required="true"' : '' !!} >{!! $params['value'] ?? '' !!}</textarea>
</div>

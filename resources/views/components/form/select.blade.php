@if (!empty($params['label']))
    <span class="form-field-label">{{ !empty($params['label']) ? $params['label'] : ucfirst($name) }}{!! !empty($params['hint']) ? ' <span class="hint">'.$params['hint'].'</span>' : '' !!}:{!! !empty($params['required']) ? '<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="'. (!empty($params['title']) ? $params['title'] : 'this field is required') .'"></i>' : '' !!}</span>
@endif
<label class="field select">
    {!! Form::select($name, $data, !empty($params['selected']) ? $params['selected'] : null, array_merge( ['class' => 'grayed'.(!empty($params['class']) ? ' '.$params['class'] : '')], !empty($params['attributes']) ? $params['attributes'] : [])) !!}
    <i class="arrow double"></i>
    <span class="field-icon"><i class="{{ !empty($params['iconClass']) ? $params['iconClass'] : 'fa fa-bookmark' }}"></i></span>
</label>
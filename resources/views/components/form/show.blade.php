<div class="show-container{{ !empty($params['class']) ? ' '.$params['class'] : '' }}">
    @if (!empty($params['label']))
        <span class="form-field-label">{{ !empty($params['label']) ? $params['label'] : ucfirst($name) }}{!! !empty($params['hint']) ? ' <span class="hint">'.$params['hint'].'</span>' : '' !!}:{!! !empty($params['required']) ? '<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="'. (!empty($params['title']) ? $params['title'] : 'this field is required') .'"></i>' : '' !!}</span>
    @endif
    <div class="form-field-value{{ !empty($params['iconClass']) ? ' prepend-icon' : '' }}">
        <span{!! !empty($params['id']) ? ' id="'.$params['id'].'"' : '' !!}>{!! $value !!}</span>
        @if (!empty($params['iconClass']))
            <span class="field-icon"><i class="{{ $params['iconClass'] }}"></i></span>
        @endif
    </div>
</div>
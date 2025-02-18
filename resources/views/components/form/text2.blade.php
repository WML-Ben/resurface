{{-- 2017-08-18 --}}
<span class="form-field-label">{{ !empty($params['label']) ? $params['label'] : ucfirst($name) }}{!! !empty($params['hint']) ? ' <span class="hint">'.$params['hint'].'</span>' : '' !!}:{!! !empty($params['required']) ? '<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="'. (!empty($params['title']) ? $params['title'] : 'this field is required') .'"></i>' : '' !!}</span>
<label for="{{ !empty($params['id']) ? $params['id'] : $name }}" class="field prepend-icon">
    <input type="text"
       value="{{ !empty($params['value']) ? $params['value'] : '' }}"
       class="{{ 'gui-input'. (!empty($params['class']) ? ' '.$params['class'] : '') }}"
       id="{{ !empty($params['id']) ? $params['id'] : $name }}"
       name="{{ $name }}"
       placeholder="{{ !empty($params['placeholder']) ? $params['placeholder'] : '' }}"
    @if (!empty($params['attributes']))
        @foreach ($params['attributes'] as $key => $value)
        {{ ' ' . $key .'="'. $value .'"' }}
            @endforeach
        @endif
    >
    <span class="field-icon"><i class="{{ !empty($params['iconClass']) ? $params['iconClass'] : 'fa fa-sticky-note-o' }}"></i></span>
</label>
<div class="form-group mb0 jmultiselect-widget{{ !empty($params['icon']) && $params['icon'] === true ? ' has-icons' : '' }}">
    @if (!empty($params['label']))
        <span class="db form-field-label">{{ !empty($params['label']) ? $params['label'] : ucfirst($name) }}{!! !empty($params['hint']) ? ' <span class="hint">'.$params['hint'].'</span>' : '' !!}:{!! !empty($params['required']) ? '<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="'. (!empty($params['title']) ? $params['title'] : 'this field is required') .'"></i>' : '' !!}</span>
    @endif
    <label class="field select">
        <select name="{{ $name }}" id="{{ !empty($params['id']) ? $params['id'] : $name }}" class="{{ !empty($params['class']) ? $params['class'] : '' }}"
            @if (!empty($params['attributes']))
                @foreach ($params['attributes'] as $key => $value)
                    {{ ' ' . $key .'="'. $value .'"' }}
                    @endforeach
                @endif
            >
            @foreach ($data as $key => $value)
                <option value="{{ $key }}"{!! !empty($params['selected']) && in_array($key, $params['selected']) ? ' selected="selected"' : ''  !!} data-key="{{ $key }}">{{ $value }}</option>
            @endforeach
        </select>
        @if (!empty($params['icon']) && $params['icon'] === true)
            <i class="arrow double"></i>
            <span class="field-icon"><i class="{{ !empty($params['iconClass']) ? $params['iconClass'] : 'fa fa-sticky-note-o' }}"></i></span>
        @endif
    </label>
</div>

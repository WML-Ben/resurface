{{-- 2017-08-18 -  $('#number').spinner(); --}}
<div class="input-group w100perc">
    <span class="form-field-label">{{ !empty($params['label']) ? $params['label'] : ucfirst($name) }}{!! !empty($params['hint']) ? ' <span class="hint">'.$params['hint'].'</span>' : '' !!}:{!! !empty($params['required']) ? '<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="'. (!empty($params['title']) ? $params['title'] : 'this field is required') .'"></i>' : '' !!}</span>
    <label for="{{ !empty($params['id']) ? $params['id'] : $name }}" class="field prepend-icon">
        <input type="text"
           class="{{ 'gui-input pl36'. (!empty($params['class']) ? ' '.$params['class'] : '') }}"
           id="{{ !empty($params['id']) ? $params['id'] : $name }}"
           name="{{ $name }}"
           value="{{ !empty($params['value']) ? $params['value'] : 1 }}"
           min="{{ !empty($params['value']) ? $params['value'] : 1 }}"
           max="{{ !empty($params['max']) ? $params['max'] : 10 }}"
           step="{{ !empty($params['step']) ? $params['step'] : 1 }}"
           start="{{ !empty($params['start']) ? $params['start'] : 1 }}"
            @if (!empty($params['attributes']))
                @foreach ($params['attributes'] as $key => $value)
                    {{ ' ' . $key .'="'. $value .'"' }}
                @endforeach
            @endif
        >
        <span class="field-icon"><i class="fa fa-level-up"></i></span>
    </label>
</div>


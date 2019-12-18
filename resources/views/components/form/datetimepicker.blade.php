{{-- 2017-08-11 --}}
<span class="form-field-label">{{ !empty($params['label']) ? $params['label'] : ucfirst($name) }}{!! !empty($params['hint']) ? ' <span class="hint">'.$params['hint'].'</span>' : '' !!}:{!! !empty($params['required']) ? '<i class="field-required fa fa-asterisk" data-toggle="tooltip" title="'. (!empty($params['title']) ? $params['title'] : 'this field is required') .'"></i>' : '' !!}</span>
<label for="{{ !empty($params['id']) ? $params['id'] : $name }}" class="field prepend-icon">
    <input type="text"
           value="{{ !empty($params['value']) ? $params['value']->format(!empty($params['language']) && $params['language'] == 'es' ? 'd-n-Y g:i A' : 'n/d/Y g:i A') : '' }}"
           class="{{ 'gui-input bootstrap-datetime-picker'. (!empty($params['language']) && $params['language'] == 'es' ? '-es' : '') . (!empty($params['class']) ? ' '.$params['class'] : '') }}"
           id="{{ !empty($params['id']) ? $params['id'] : $name }}"
           name="{{ $name }}"
           placeholder="{{ !empty($params['placeholder']) ? $params['placeholder'] : '' }}"
    @if (!empty($params['attributes']))
        @foreach ($params['attributes'] as $key => $value)
            {{ ' ' . $key .'="'. $value .'"' }}
        @endforeach
    @endif
    >
    <span class="field-icon"><i class="{{ !empty($params['iconClass']) ? $params['iconClass'] : 'fa fa-calendar-o' }}"></i></span>
</label>
{{--
    when value is not null, create own imput field to avoid format issue when using Form::model
<input type="text" id="datetimepicker" class="datetime-picker" data-date="5/1/2017" value="5/1/2017 1:02 PM" data-date-format="m/dd/yyyy hh:ii P">

to convert to carbon instance:
     $startedAt = \Carbon\Carbon::createFromFormat('m/d/Y h:i A', $request->started_at);

--}}
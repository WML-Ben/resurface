<span class="switch block switch-primary{{ !empty($params['class']) ? ' '.$params['class'] : '' }}">
    <input type="checkbox" name="{{ $name }}" id="{{ !empty($params['id']) ? $params['id'] : $name }}" value="1" {{ !empty($params['checked']) ? 'checked' : ''}}>
    <label for="{{ !empty($params['id']) ? $params['id'] : $name }}" data-on="{{ !empty($params['on']) ? $params['on'] : 'YES' }}" data-off="{{ !empty($params['off']) ? $params['off'] : 'NO' }}" class="switch-object"></label>
    <span class="switch-label">{{ !empty($params['label']) ? $params['label'] : '' }}</span>
</span>
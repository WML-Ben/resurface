<div class="smart-widget sm-left sml-120">
    <label for="{{ !empty($params['id']) ? $params['id'] : 'verification' }}" class="field prepend-icon">
        <input name="{{ !empty($params['name']) ? $params['name'] : 'verification' }}" id="{{ !empty($params['id']) ? $params['id'] : 'verification' }}" class="gui-input" placeholder="{{ !empty($params['placeholder']) ? $params['placeholder'] : 'Solve equation' }}" type="text">
        <span class="field-icon"><i class="{{ !empty($params['iconClass']) ? $params['iconClass'] : 'fa fa-shield' }}"></i></span>
    </label>
    <label for="{{ !empty($params['id']) ? $params['id'] : 'verification' }}" class="button">{{ $equation }} =</label>
</div>
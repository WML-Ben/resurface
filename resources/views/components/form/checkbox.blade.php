<label class="mt15 option option-primary"{!! !empty($params['title']) ? ' data-toggle="tooltip" title="'.$params['title'].'"' : '' !!}>
    <input type="checkbox" name="{{ $name }}" value="1" {!! !empty($params['id']) ? ' id="'.$params['id'].'"' : '' !!}>
    <span class="checkbox"></span>{!! !empty($params['label']) ? $params['label'] : 'label' !!}
</label>
@if (!empty($object->featured))
    <div class="ribbon-tag featured{!! !empty($class) ? ' '. $class : '' !!}"></div>
@elseif (!empty($object->popular))
    <div class="ribbon-tag popular{!! !empty($class) ? ' '. $class : '' !!}"></div>
@elseif (!empty($object->hot_deal))
    <div class="ribbon-tag hot-deal{!! !empty($class) ? ' '. $class : '' !!}"></div>
@endif
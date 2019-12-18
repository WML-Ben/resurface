{!! Form::open(['method'=>'DELETE', 'url' => $route, 'id' => 'deleteForm']) !!}
    <input type="hidden" id="form_delete_item_id" name="item_id">
{!! Form::close() !!}
<div class="panel-body">
    <div class="section-divider mb40 mt20"><span>{{ $formTitle }}</span></div>
    <div class="section row">
        <div class="col-md-6 admin-form-item-widget">
            {{ Form::jText('item_key', ['label' => 'Item Name', 'id' => 'item_key', 'placeholder' => 'Item Name', 'required' => true, 'iconClass' => 'fa fa-cog']) }}
        </div>
        <div class="col-md-6 admin-form-item-widget">
            {{ Form::jText('item_value', ['label' => 'Item Value', 'id' => 'item_value', 'placeholder' => 'Item Value', 'required' => true]) }}
        </div>
    </div>
</div>
<div class="panel-footer text-right">
    <div class="row">
        <div class="col-md-12">
            {{ Form::jCancelSubmit(['submit-label' => $submitButtonText]) }}
        </div>
    </div>
</div>
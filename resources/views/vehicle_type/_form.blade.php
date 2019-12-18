<div class="panel-body">
    <div class="section-divider mb20 mt20"><span>{{ $formTitle }}</span></div>

    <div class="row">
        <div class="col-sm-12 admin-form-item-widget">
            {{ Form::jText('name', ['label' => 'Name', 'id' => 'name', 'required' => true, 'iconClass' => 'fa fa-truck']) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 admin-form-item-widget">
            {{ Form::jTextarea('description', ['label' => 'Description', 'id' => 'description', 'required' => false, 'iconClass' => 'fa fa-circle']) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3 col-xs-6 admin-form-item-widget">
            {{ Form::jText('rate', ['value' => $vehicleType->rate_float ?? null, 'label' => 'Rate', 'id' => 'rate', 'required' => true, 'iconClass' => 'fa fa-dollar']) }}
        </div>
    </div>
</div>
<div class="panel-footer text-right">
    <div class="row">
        <div class="col-sm-12">
            {{ Form::jCancelSubmit(['submit-label' => $submitButtonText]) }}
        </div>
    </div>
</div>
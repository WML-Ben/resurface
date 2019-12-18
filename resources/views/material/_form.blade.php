<div class="panel-body">
    <div class="section-divider mb20 mt20"><span>{{ $formTitle }}</span></div>

    <div class="row">
        <div class="col-sm-12 admin-form-item-widget">
            {{ Form::jText('name', ['label' => 'Name', 'id' => 'name', 'required' => true, 'iconClass' => 'fa fa-road']) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3 col-xs-6 admin-form-item-widget">
            {{ Form::jText('cost', ['value' => $material->cost_float ?? null, 'label' => 'Cost', 'id' => 'cost', 'required' => true, 'iconClass' => 'fa fa-dollar']) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3 col-xs-6 admin-form-item-widget">
            {{ Form::jText('alt_cost', ['value' => $material->alt_cost_float ?? null, 'label' => 'Alt cost', 'id' => 'alt_cost', 'required' => true, 'iconClass' => 'fa fa-dollar']) }}
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


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
        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jSelect('type_id', $vehicle_types_cb, ['label' => 'Vehicle Type', 'selected' => ($vehicle->type_id ?? 0), 'id' => 'type_id', 'required' => true, 'iconClass' => 'icon-key']) }}
        </div>
        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jSelect('location_id', $locations_cb, ['label' => 'Location', 'selected' => ($vehicle->location_id ?? 0), 'id' => 'location_id', 'required' => true, 'iconClass' => 'icon-key']) }}
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jCalendar('purchased_at', ['value' => ($vehicle->purchased_at ?? null), 'label' => 'Purchase Date', 'id' => 'purchased_at', 'placeholder' => '', 'iconClass' => 'fa fa-calendar']) }}
        </div>
        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jText('vin_number', ['label' => 'Vin Number', 'id' => 'vin_number', 'placeholder' => '', 'required' => false, 'iconClass' => 'fa fa-hashtag']) }}
        </div>
    </div>
    @if (!empty($vehicle))
        <div class="row mt10">
            <div class="col-md-12 admin-form-item-widget">
                {{ Form::jSwitch('disabled', ['label' => 'Disabled', 'id' => 'disabled', 'checked' => !empty($vehicle->disabled)]) }}
            </div>
        </div>
    @endif
</div>
<div class="panel-footer text-right">
    <div class="row">
        <div class="col-sm-12">
            {{ Form::jCancelSubmit(['submit-label' => $submitButtonText]) }}
        </div>
    </div>
</div>
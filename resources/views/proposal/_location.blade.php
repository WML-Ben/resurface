{{-- $proposal --}}
<div class="portlet blue-hoki box" id="service_location_section">
    <div class="portlet-title">
        <div class="caption">Service Location</div>
    </div>
    <div class="portlet-body">
        <div class="row">
            <div class="col-sm-12 col-md-6 admin-form-item-widget">
                {{ Form::jText('address', ['value' => $obj->address ?? null, 'label' => 'Address', 'id' => 'address', 'placeholder' => '', 'required' => false, 'iconClass' => 'fa fa-map-marker']) }}
            </div>
            <div class="col-sm-6 col-md-3 admin-form-item-widget">
                {{ Form::jText('address_2', ['value' => $obj->address_2 ?? null, 'label' => 'Address 2', 'id' => 'address_2', 'placeholder' => '', 'required' => false, 'iconClass' => 'fa fa-home']) }}
            </div>
            <div class="col-sm-6 col-md-3 admin-form-item-widget">
                {{ Form::jText('parcel_number', ['value' => $obj->parcel_number ?? null, 'label' => 'Parcel Number', 'id' => 'parcel_number', 'placeholder' => '', 'required' => false, 'iconClass' => 'fa fa-bookmark']) }}
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-md-4 admin-form-item-widget">
                {{ Form::jText('city', ['value' => $obj->city ?? null, 'label' => 'City', 'id' => 'city', 'placeholder' => '', 'required' => false, 'iconClass' => 'fa fa-building']) }}
            </div>
            <div class="col-xs-12 col-sm-4 col-md-2 admin-form-item-widget">
                {{ Form::jText('zipcode', ['value' => $obj->zipcode ?? null, 'label' => 'Zip Code', 'id' => 'zipcode', 'placeholder' => '', 'required' => false, 'iconClass' => 'fa fa-support']) }}
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 admin-form-item-widget">
                {{ Form::jSelect('country_id', $countriesCB, ['label' => 'Country', 'selected' => $obj->country_id ?? 231, 'required' => false, 'iconClass' => 'fa fa-map-o', 'attributes' => ['id' => 'country_id']]) }}
            </div>
            <div class="col-xs-12 col-sm-6 col-md-3 admin-form-item-widget">
                {{ Form::jSelect('state_id', $statesCB, ['label' => 'State', 'selected' => $obj->state_id ?? 3930, 'required' => false, 'iconClass' => 'fa fa-map-pin', 'attributes' => ['id' => 'state_id']]) }}
            </div>
        </div>
    </div>
</div>
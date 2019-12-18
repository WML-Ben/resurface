<div class="panel-body">
    <div class="section-divider mb25 mt20"><span>{{ $formTitle }}</span></div>
    <div class="row">
        <div class="col-md-12 admin-form-item-widget">
            {{ Form::jText('name', ['label' => 'Name', 'id' => 'name', 'placeholder' => '', 'required' => true]) }}
        </div>
    </div>
    <div class="row">
         <div class="col-xs-12 col-sm-6 admin-form-item-widget">
                {{ Form::jSelect('manager_id', $managersCB, ['label' => 'Location Manager', 'selected' => $location->manager_id ?? 231, 'required' => false, 'iconClass' => 'fa fa-map-o', 'attributes' => ['id' => 'manager_id']]) }}
         </div>
    </div>
    {{--
    <div class="row">
        <div class="col-sm-7 admin-form-item-widget">
            {{ Form::jText('email', ['label' => 'Email', 'id' => 'email', 'placeholder' => '', 'required' => false, 'iconClass' => 'fa fa-envelope']) }}
        </div>
        <div class="col-sm-5 admin-form-item-widget">
            {{ Form::jText('phone', ['label' => 'Phone', 'id' => 'phone', 'placeholder' => '', 'required' => false, 'iconClass' => 'fa fa-phone']) }}
        </div>
    </div>
    --}}
    <div class="row">
        <div class="col-xs-12 col-sm-12 admin-form-item-widget">
            {{ Form::jText('address', ['label' => 'Address', 'id' => 'address', 'placeholder' => '', 'required' => true, 'iconClass' => 'fa fa-map-marker']) }}
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 admin-form-item-widget">
            {{ Form::jText('address2', ['label' => 'Address 2', 'id' => 'address2', 'placeholder' => '', 'required' => true, 'iconClass' => 'fa fa-map-marker']) }}
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-8 admin-form-item-widget">
            {{ Form::jText('city', ['label' => 'City', 'id' => 'city', 'placeholder' => '', 'required' => true, 'iconClass' => 'fa fa-building']) }}
        </div>
        <div class="col-xs-12 col-sm-4 admin-form-item-widget">
            {{ Form::jText('zipcode', ['label' => 'Zip Code', 'id' => 'zipcode', 'placeholder' => '', 'required' => true, 'iconClass' => 'fa fa-support']) }}
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-6 admin-form-item-widget">
            {{ Form::jSelect('country_id', $countriesCB, ['label' => 'Country', 'selected' => $location->country_id ?? 231, 'required' => false, 'iconClass' => 'fa fa-map-o', 'attributes' => ['id' => 'country_id']]) }}
        </div>
        <div class="col-xs-12 col-sm-6 admin-form-item-widget">
            {{ Form::jSelect('state_id', $statesCB, ['label' => 'State', 'selected' => $location->state_id ?? 3930, 'required' => false, 'iconClass' => 'fa fa-map-pin', 'attributes' => ['id' => 'state_id']]) }}
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
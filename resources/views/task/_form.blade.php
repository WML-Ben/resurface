<div class="panel-body">
    <div class="section-divider mb25 mt20"><span>{{ $formTitle }}</span></div>
    <div class="row">
        <div class="col-md-12 admin-form-item-widget">
            {{ Form::jText('name', ['label' => 'Name', 'id' => 'name', 'placeholder' => '', 'required' => true]) }}
        </div>
    </div>
    <div class="row">

        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jSelect('category_id', $taskCategoriesCB, ['label' => 'Category', 'selected' => $task->category_id ?? null, 'required' => true, 'iconClass' => 'fa fa-bars', 'attributes' => ['id' => 'category_id']]) }}
        </div>
        <div class="col-sm-6 admin-form-item-widget xs-hidden"></div>
    </div>
    <div class="row">
        <div class="col-sm-7 admin-form-item-widget">
            {{ Form::jText('email', ['label' => 'Email', 'id' => 'email', 'required' => false, 'iconClass' => 'fa fa-envelope']) }}
        </div>
        <div class="col-sm-5 admin-form-item-widget">
            {{ Form::jText('phone', ['label' => 'Phone', 'id' => 'phone', 'required' => false, 'iconClass' => 'fa fa-phone']) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-7 admin-form-item-widget">
            {{ Form::jText('alt_email', ['label' => 'Alt Email', 'id' => 'alt_email', 'required' => false, 'iconClass' => 'fa fa-envelope']) }}
        </div>
        <div class="col-sm-5 admin-form-item-widget">
            {{ Form::jText('alt_phone', ['label' => 'Alt Phone', 'id' => 'alt_phone', 'required' => false, 'iconClass' => 'fa fa-phone']) }}
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 admin-form-item-widget">
            {{ Form::jText('address', ['label' => 'Address', 'id' => 'address', 'placeholder' => '', 'required' => true, 'iconClass' => 'fa fa-map-marker']) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jText('address_2', ['label' => 'Address 2', 'id' => 'address_2', 'placeholder' => '', 'required' => false, 'iconClass' => 'fa fa-home']) }}
        </div>
        <div class="col-sm-6 admin-form-item-widget">

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
            {{ Form::jSelect('country_id', $countriesCB, ['label' => 'Country', 'selected' => $task->country_id ?? 231, 'required' => true, 'iconClass' => 'fa fa-globe', 'attributes' => ['id' => 'country_id']]) }}
        </div>
        <div class="col-xs-12 col-sm-6 admin-form-item-widget">
            {{ Form::jSelect('state_id', $statesCB, ['label' => 'State', 'selected' => $task->state_id ?? 3930, 'required' => true, 'iconClass' => 'fa fa-map-pin', 'attributes' => ['id' => 'state_id']]) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 admin-form-item-widget">
            {{ Form::jSwitch('above_as_billing_address', ['label' => 'Use above as Billing Address', 'id' => 'above_as_billing_address', 'checked' => empty($task)]) }}
        </div>
    </div>
    <div id="billing_container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 admin-form-item-widget">
                {{ Form::jText('billing_address', ['label' => 'Billing Address', 'id' => 'billing_address', 'placeholder' => '', 'required' => false, 'iconClass' => 'fa fa-map-marker']) }}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 admin-form-item-widget">
                {{ Form::jText('billing_address_2', ['label' => 'Billing Address 2', 'id' => 'billing_address_2', 'placeholder' => '', 'required' => false, 'iconClass' => 'fa fa-home']) }}
            </div>
            <div class="col-sm-6 admin-form-item-widget">

            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-8 admin-form-item-widget">
                {{ Form::jText('billing_city', ['label' => 'Billing City', 'id' => 'billing_city', 'placeholder' => '', 'required' => false, 'iconClass' => 'fa fa-building']) }}
            </div>
            <div class="col-xs-12 col-sm-4 admin-form-item-widget">
                {{ Form::jText('billing_zipcode', ['label' => 'Billing Zip Code', 'id' => 'billing_zipcode', 'placeholder' => '', 'required' => false, 'iconClass' => 'fa fa-support']) }}
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-6 admin-form-item-widget">
                {{ Form::jSelect('billing_country_id', $countriesCB, ['label' => 'Billing Country', 'selected' => $task->billing_country_id ?? 231, 'required' => false, 'iconClass' => 'fa fa-globe', 'attributes' => ['id' => 'billing_country_id']]) }}
            </div>
            <div class="col-xs-12 col-sm-6 admin-form-item-widget">
                {{ Form::jSelect('billing_state_id', $statesCB, ['label' => 'Billing State', 'selected' => $task->billing_state_id ?? 3930, 'required' => false, 'iconClass' => 'fa fa-map-pin', 'attributes' => ['id' => 'billing_state_id']]) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 admin-form-item-widget">
            {{ Form::jTextarea('comment', ['label' => 'Comment', 'id' => 'comment', 'placeholder' => '', 'required' => false]) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jSwitch('qualified', ['label' => 'Qualified', 'id' => 'qualified', 'checked' => !empty($task->qualified)]) }}
        </div>
        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jSwitch('disabled', ['label' => 'Disabled', 'id' => 'disabled', 'checked' => !empty($task->disabled)]) }}
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
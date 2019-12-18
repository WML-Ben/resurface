<div class="panel-body">
    <div class="section-divider mb25 mt20"><span>{{ $formTitle }}</span></div>
    <div class="row">
        <div class="col-md-6 admin-form-item-widget">
            {{ Form::jText('name', ['label' => 'Property Name', 'id' => 'name', 'placeholder' => '', 'required' => true]) }}
        </div>
		<div class="col-sm-6 admin-form-item-widget">
            @if (empty($property->id))
                <span class="top-right-field-link" id="new_owner_link_container">
                    <a href="javascript:;" id="new_owner_link" class="new-owner-link">New</a>
                </span>
                <div id="owner_show_container" class="show-wrapper hidden">
                    {{ Form::jShow('&nbsp;', ['label' => 'Property Owner', 'id' => 'show_owner_name', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-sitemap']) }}
                </div>
            @endif
            <div id="owner_select2_container" class="">
                {{ Form::jSelect2('owner_id', [], ['label' => 'Property Owner', 'selected' => $property->owner_id ?? null, 'required' => false, 'iconClass' => 'fa fa-user', 'attributes' => ['id' => 'owner_id']]) }}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 admin-form-item-widget">
            <!-- only on create, not update  -->
            @if (empty($property->id))
                <span class="top-right-field-link" id="new_company_link_container">
                    <a href="javascript:;" id="new_company_link" class="new-company-link">New</a>
                </span>
                <div id="company_show_container" class="show-wrapper hidden">
                    {{ Form::jShow('&nbsp;', ['label' => 'Management Company', 'id' => 'show_company_name', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-sitemap']) }}
                </div>
            @endif
            <div id="company_select2_container" class="">
                {{ Form::jSelect2('company_id', [], ['label' => 'Management Company', 'selected' => $property->company_id ?? null, 'required' => false, 'iconClass' => 'fa fa-sitemap', 'attributes' => ['id' => 'company_id']]) }}
            </div>
        </div>
        <div class="col-sm-6 admin-form-item-widget">
		{{--{!! Form::hidden('manager_id', $property->manager_id ?? null, ['id' => 'manager_id']) !!}--}}
            <div id="manager_select_container" class="">
                {{ Form::jSelect2('manager_id', $managersCB, ['label' => 'Management Contact', 'selected' => $property->manager_id ?? null, 'required' => false, 'iconClass' => 'fa fa-user', 'attributes' => ['id' => 'company_manager_id']]) }}
            </div>
            <!-- only on create, not update  -->
            @if (empty($property->id))
                <span class="top-right-field-link{{ empty($property->company_id) ? ' hidden' : '' }}" id="new_manager_link_container">
                    <a href="javascript:;" id="new_manager_link" class="new-manager-link">New</a>
                </span>
                <div id="manager_show_container" class="show-wrapper hidden">
                    {{ Form::jShow('&nbsp;', ['label' => 'Management Contact', 'id' => 'show_manager_name', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-user']) }}
                </div>
                <div id="manager_select2_container" class="hidden">
                    {{ Form::jSelect2('contact_manager_id', [], ['label' => 'Management Contact', 'selected' => null, 'required' => false, 'iconClass' => 'fa fa-user', 'attributes' => ['id' => 'new_manager_id']]) }}
                </div>
            @endif
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
        <div class="col-xs-12 col-sm-6 admin-form-item-widget">
            {{ Form::jText('address', ['label' => 'Address', 'id' => 'address', 'placeholder' => '', 'required' => true, 'iconClass' => 'fa fa-map-marker']) }}
        </div>
		<div class="col-xs-12 col-sm-6 admin-form-item-widget">
            {{ Form::jSelect('state_id', $statesCB, ['label' => 'State', 'selected' => $property->state_id ?? 3930, 'required' => false, 'iconClass' => 'fa fa-map-pin', 'attributes' => ['id' => 'state_id']]) }}
        </div>
    </div>
    <!--<div class="row">
        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jText('address_2', ['label' => 'Address 2', 'id' => 'address_2', 'placeholder' => '', 'required' => false, 'iconClass' => 'fa fa-home']) }}
        </div>
        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jText('parcel_number', ['label' => 'Parcel Number', 'id' => 'parcel_number', 'placeholder' => '', 'required' => false, 'iconClass' => 'fa fa-bookmark']) }}
        </div>
    </div>-->
    <div class="row">
        <div class="col-xs-12 col-sm-6 admin-form-item-widget">
            {{ Form::jText('city', ['label' => 'City', 'id' => 'city', 'placeholder' => '', 'required' => true, 'iconClass' => 'fa fa-building']) }}
        </div>
        <div class="col-xs-12 col-sm-6 admin-form-item-widget">
            {{ Form::jText('zipcode', ['label' => 'Zip Code', 'id' => 'zipcode', 'placeholder' => '', 'required' => true, 'iconClass' => 'fa fa-support']) }}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 admin-form-item-widget">
            {{ Form::jTextarea('comment', ['label' => 'Comment', 'id' => 'comment', 'placeholder' => '', 'required' => false]) }}
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
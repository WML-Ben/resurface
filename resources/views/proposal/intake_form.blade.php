@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><span>Intake Form</span></li>
        </ul>
    </div>
@stop

@section('content')
    <section id="content" class="animated fadeIn list-items admin-form">
        <div class="row intake-form">
            <div class="col-md-9 center-block">
                @include('errors._list')
                <div class="admin-form theme-primary">
                    <div class="panel">
                        {!! Form::open(['url' => route('proposal_intake_form_store'), 'id' => 'createIntakeForm']) !!}
                            <div class="panel-body">
                                <h2 id="property_header" class="form-section-divider mt0">{{ !empty($property) ? 'Property Details' : 'Select Property' }}</h2>

                                <div class="row">
                                    <div class="col-sm-12 admin-form-item-widget posrel">
                                        @if (empty($property))
                                            {{--  No Property defined yet:  --}}

                                            {!! Form::hidden('property_id', null, ['id' => 'property_id']) !!}
                                            {!! Form::hidden('name', null, ['id' => 'name']) !!}

                                            <div id="properties_container" class="">
                                                <span class="top-right-field-link">
                                                    <a href="{{ route('property_create', ['returnTo' => 'proposal_intake_form_create']) }}">New</a>
                                                </span>
                                                {{ Form::jSelect2('new_property_id', [], ['label' => 'Properties', 'selected' => null, 'required' => false, 'iconClass' => 'fa fa-building', 'attributes' => ['id' => 'new_property_id']]) }}
                                            </div>
                                        @else
                                            {{--  Property was created:  --}}
                                            {!! Form::hidden('property_id', $property->id, ['id' => 'property_id']) !!}
                                            {!! Form::hidden('name', $property->name, ['id' => 'name']) !!}

                                            <div id="property_show_container" class="show-wrapper">
                                                {{ Form::jShow($property->name ?? '&nbsp;', ['label' => 'Property', 'id' => 'show_property_name', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-building']) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div id="data-container" class="{{ empty($property) ? 'hidden' : '' }}">
                                    <div class="row">
                                        <div class="col-sm-6 admin-form-item-widget">
                                            {!! Form::hidden('owner_id', $property->owner_id ?? null, ['id' => 'owner_id']) !!}

                                            <div id="property_owner_show_container" class="show-wrapper">
                                                <span class="top-right-field-link">
                                                    <a href="javascript:;" id="show_property_owner_list_link">Contacts</a>
                                                </span>
                                                {{ Form::jShow($property->owner->fullName ?? '&nbsp;', ['label' => 'Owner', 'id' => 'show_property_owner_name', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-user']) }}
                                            </div>
                                            <div id="property_owners_container" class="hidden">
                                                <span class="top-right-field-link">
                                                    <a href="javascript:;" id="cancel_change_property_owner_link">Reset</a>
                                                </span>
                                                {{ Form::jSelect2('new_owner_id', [], ['label' => 'Owner', 'selected' => $property->owner_id ?? null, 'required' => false, 'iconClass' => 'fa fa-user', 'attributes' => ['id' => 'new_owner_id']]) }}
                                            </div>
                                        </div>
                                        <div class="col-sm-6 admin-form-item-widget">
                                            {{ Form::jText('parcel_number', ['label' => 'Parcel Number', 'id' => 'parcel_number', 'required' => false, 'iconClass' => 'icon-pin']) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 admin-form-item-widget">
                                            {{ Form::jText('address', ['value' => ($property->address ?? null), 'label' => 'Address', 'id' => 'address', 'required' => false, 'iconClass' => 'icon-location']) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3 admin-form-item-widget">
                                            {{ Form::jText('address_2', ['value' => ($property->address_2 ?? null), 'label' => 'Address 2', 'id' => 'address_2', 'placeholder' => '', 'required' => false, 'iconClass' => 'icon-location']) }}
                                        </div>
                                        <div class="col-sm-6 admin-form-item-widget">
                                            {{ Form::jText('city', ['value' => ($property->city ?? null), 'label' => 'City', 'id' => 'city', 'required' => false, 'iconClass' => 'icon-building']) }}
                                        </div>
                                        <div class="col-sm-3 admin-form-item-widget">
                                            {{ Form::jText('zipcode', ['value' => ($property->zipcode ?? null), 'label' => 'Zip Code', 'id' => 'zipcode', 'required' => false, 'iconClass' => 'fa fa-map-o']) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 admin-form-item-widget">
                                            {{ Form::jSelect('country_id', $countriesCB, ['label' => 'Country', 'selected' => ($property->country_id ?? 231), 'required' => false, 'iconClass' => 'fa fa-globe', 'attributes' => ['id' => 'country_id']]) }}
                                        </div>
                                        <div class="col-sm-6 admin-form-item-widget">
                                            {{ Form::jSelect('state_id', $statesCB, ['label' => 'State', 'selected' => ($property->state_id ?? 3930), 'required' => false, 'iconClass' => 'fa fa-map-pin', 'attributes' => ['id' => 'state_id']]) }}
                                        </div>
                                    </div>
                                    {{--
                                    <div class="section-divider mb25 mt20"><span>Company</span></div>
                                    --}}
                                    <h2 class="form-section-divider">Company Details</h2>

                                    <div class="row company-row">
                                        <div class="col-sm-12 admin-form-item-widget posrel">
                                            {!! Form::hidden('company_id', $property->company_id ?? null, ['id' => 'company_id']) !!}

                                            <div id="company_show_container" class="show-wrapper">
                                                <span class="top-right-field-link">
                                                    <a href="javascript:;" id="show_companies_list_link">Companies</a>
                                                    <span> | </span>
                                                    <a href="javascript:;" id="new_company_from_show_company_link" class="new-company-link">New</a>
                                                </span>
                                                {{ Form::jShow($property->company->name ?? '&nbsp;', ['label' => 'Company', 'id' => 'show_company_name', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-building']) }}
                                            </div>
                                            <div id="companies_container" class="hidden">
                                                <span class="top-right-field-link">
                                                    <a href="javascript:;" id="new_company_from_companies_link" class="new-company-link">New</a>
                                                    <span> | </span>
                                                    <a href="javascript:;" id="cancel_change_company_link">Reset</a>
                                                </span>
                                                {{ Form::jSelect2('new_company_id', [], ['label' => 'Company', 'selected' => null, 'required' => false, 'iconClass' => 'fa fa-building', 'attributes' => ['id' => 'new_company_id']]) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 admin-form-item-widget">
                                            {{ Form::jText('billing_address', ['value' => ($property->company->billing_address ?? null), 'label' => 'Billing Address', 'id' => 'billing_address', 'required' => false, 'iconClass' => 'icon-location']) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-3 admin-form-item-widget">
                                            {{ Form::jText('billing_address_2', ['value' => ($property->company->billing_address_2 ?? null), 'label' => 'Billing Address 2', 'id' => 'billing_address_2', 'placeholder' => '', 'required' => false, 'iconClass' => 'icon-location']) }}
                                        </div>
                                        <div class="col-sm-6 admin-form-item-widget">
                                            {{ Form::jText('billing_city', ['value' => ($property->company->billing_city ?? null), 'label' => 'Billing City', 'id' => 'billing_city', 'required' => false, 'iconClass' => 'icon-building']) }}
                                        </div>
                                        <div class="col-sm-3 admin-form-item-widget">
                                            {{ Form::jText('billing_zipcode', ['value' => ($property->company->billing_zipcode ?? null), 'label' => 'Billing Zip Code', 'id' => 'billing_zipcode', 'required' => false, 'iconClass' => 'fa fa-map-o']) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 admin-form-item-widget">
                                            {{ Form::jSelect('billing_country_id', $countriesCB, ['label' => 'Billing Country', 'selected' => ($property->company->billing_country_id ?? null), 'required' => false, 'iconClass' => 'fa fa-globe', 'attributes' => ['id' => 'billing_country_id']]) }}
                                        </div>
                                        <div class="col-sm-6 admin-form-item-widget">
                                            {{ Form::jSelect('billing_state_id', $statesCB, ['label' => 'Billing State', 'selected' => ($property->company->billing_state_id ?? null), 'required' => false, 'iconClass' => 'fa fa-map-pin', 'attributes' => ['id' => 'billing_state_id']]) }}
                                        </div>
                                    </div>

                                    <div class="row manager-row">
                                        <div class="col-sm-6 admin-form-item-widget posrel">
                                            {!! Form::hidden('manager_id', $property->manager_id ?? null, ['id' => 'manager_id']) !!}

                                            <div id="company_manager_show_container" class="show-wrapper">
                                                <span class="top-right-field-link">
                                                    <span class="company-users-link-container{{ empty($managersCB) ? ' hidden' : '' }}">
                                                        <a href="javascript:;" id="show_company_users_from_show_manager_link">Company Users</a>
                                                        <span> | </span>
                                                    </span>
                                                    <a href="javascript:;" id="show_contacts_list_fom_show_manager_link">Contacts</a>
                                                    <span> | </span>
                                                    <a href="javascript:;" id="new_manager_from_show_manager_link" class="new-manager-link">New</a>
                                                </span>
                                                {{ Form::jShow($property->manager->fullName ?? '&nbsp;', ['label' => 'Manager', 'id' => 'show_manager_name', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-building']) }}
                                            </div>
                                            <div id="company_users_container" class="hidden">
                                                 <span class="top-right-field-link">
                                                    <a href="javascript:;" id="show_contacts_list_from_company_users_link">Contacts</a>
                                                    <span> | </span>
                                                    <a href="javascript:;" id="new_manager_from_company_users_link" class="new-manager-link">New</a>
                                                    <span> | </span>
                                                    <a href="javascript:;" id="cancel_change_manager_from_company_users_link" class="cancel-change-manager-link reset">Reset</a>
                                                </span>
                                                {{ Form::jSelect('new_company_user_id', ($managersCB ?? []), ['label' => 'Manager', 'selected' => null, 'required' => false, 'iconClass' => 'fa fa-user', 'attributes' => ['id' => 'new_company_user_id']]) }}
                                            </div>
                                            <div id="contacts_container" class="hidden">
                                                <span class="top-right-field-link">
                                                    <span class="company-users-link-container{{ empty($managersCB) ? ' hidden' : '' }}">
                                                        <a href="javascript:;" id="show_company_users_from_contacts_link">Company Users</a>
                                                        <span> | </span>
                                                    </span>
                                                    <a href="javascript:;" id="new_manager_from_contacts_link" class="new-manager-link">New</a>
                                                    <span class="reset-link-container">
                                                         <span> | </span>
                                                    <a href="javascript:;" id="cancel_change_manager_from_contacts_link" class="cancel-change-manager-link reset">Reset</a>
                                                    </span>
                                                </span>
                                                {{ Form::jSelect2('new_contact_id', [], ['label' => 'Manager', 'selected' => null, 'required' => false, 'iconClass' => 'fa fa-user', 'attributes' => ['id' => 'new_contact_id']]) }}
                                            </div>
                                        </div>
                                        <div class="col-sm-6 xs-hidden"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6 admin-form-item-widget">
                                            {{ Form::jText('email', ['value' => $property->manager->email ?? null, 'label' => 'Email', 'id' => 'email', 'required' => false, 'iconClass' => 'fa fa-envelope']) }}
                                        </div>
                                        <div class="col-sm-6 admin-form-item-widget">
                                            {{ Form::jText('phone', ['value' => $property->manager->phone ?? null, 'label' => 'Phone', 'id' => 'phone', 'required' => false, 'iconClass' => 'fa fa-phone']) }}
                                        </div>
                                    </div>

                                    <h2 class="form-section-divider">Assign Sales Manager</h2>

                                    <div class="row">
                                        <div class="col-sm-6 admin-form-item-widget">
                                            {{ Form::jSelect('sales_manager_id', $salesManagersCB, ['label' => 'Sales Manager', 'selected' => null, 'required' => false, 'iconClass' => 'fa fa-user', 'attributes' => ['id' => 'sales_manager_id']]) }}
                                        </div>
                                        <div class="col-sm-6 admin-form-item-widget">
                                            <div id="sales_person_container" class="hidden">
                                                {{ Form::jSelect('sales_person_id', $salesPersonsCB, ['label' => 'Sales Associate', 'selected' => null, 'required' => false, 'iconClass' => 'fa fa-user', 'attributes' => ['id' => 'sales_person_id']]) }}
                                            </div>
                                        </div>
                                    </div>

                                    <div id="create_appointment_container" class="hidden">
                                        <h2 class="form-section-divider">Create an Appointment</h2>

                                        <div class="row">
                                            <div class="col-sm-6 admin-form-item-widget">
                                                {{ Form::jDateTimePicker('event_started_at', ['value' => ($property->calendarEvent->event_started_at ?? null), 'label' => 'From', 'id' => 'event_started_at', 'required' => false, 'iconClass' => 'fa fa-calendar']) }}
                                            </div>
                                            <div class="col-sm-6 admin-form-item-widget">
                                                {{ Form::jDateTimePicker('event_ended_at', ['value' => ($property->calendarEvent->event_ended_at ?? null), 'label' => 'To', 'hint' => '(1 hour later if blank)', 'id' => 'event_ended_at', 'required' => false, 'iconClass' => 'fa fa-calendar']) }}
                                            </div>
                                        </div>
                                        {{--
                                        <div class="row">
                                            <div class="col-sm-12 admin-form-item-widget">
                                                {{ Form::jText('event_name', ['label' => 'Title', 'id' => 'event_name', 'required' => false, 'iconClass' => 'fa fa-bookmark']) }}
                                            </div>
                                        </div>
                                        --}}
                                        <div class="row">
                                            <div class="col-sm-12 admin-form-item-widget">
                                                {{ Form::jTextarea('event_description', ['label' => 'Note', 'id' => 'event_description', 'required' => false, 'iconClass' => 'fa fa-comment']) }}
                                            </div>
                                        </div>
                                    </div>

                                    <h2 class="form-section-divider">Question Details</h2>

                                    <div class="row">
                                        <div class="col-sm-6 admin-form-item-widget">
                                            {{ Form::jMultiSelect('service_category_ids', $serviceCategoriesCB, ['label' => 'What Services were you looking for?', 'id' => 'service_category_ids', 'required' => false, 'iconClass' => 'icon-wrench-1']) }}
                                        </div>
                                        <div class="col-sm-6 admin-form-item-widget">
                                            {{ Form::jRadioSelect('how_did_you_hear_about_us', ['Google' => 'Google', 'Mailer' => 'Mailer', 'Referral' => 'Referral'], ['label' => 'How did you hear about us?', 'id' => 'how_did_you_hear_about_us', 'required' => false, 'iconClass' => 'icon-wrench-1']) }}
                                        </div>
                                    </div>
                                    <div id="referring_person_container" class="row hidden">
                                        <div class="col-sm-12 admin-form-item-widget">
                                            {{ Form::jText('referring_person', ['label' => 'Referring Person Name', 'id' => 'referring_person', 'required' => false, 'iconClass' => 'fa fa-user']) }}
                                        </div>
                                    </div>

                                    {{--
                                    <span class="form-field-label">How did you hear about us?:</span>
                                    <div class="gui-input pl14 h43">
                                        <label class="radio-inline mr10">
                                            <input class="top3" name="how_did_you_hear_about_us" id="inlineRadio1" value="Google" type="radio">
                                            Google
                                        </label>
                                        <label class="radio-inline mr10">
                                            <input class="top3" name="how_did_you_hear_about_us" id="inlineRadio2" value="Mailer" type="radio">
                                            Mailer
                                        </label>
                                        <label class="radio-inline mr10">
                                            <input class="top3" name="how_did_you_hear_about_us" id="inlineRadio3" value="Referral" type="radio">
                                            Referral
                                        </label>
                                    </div>
                                    --}}
                                </div>
                            </div>
                            <div class="panel-footer text-right">
                                <div class="row">
                                    <div class="col-sm-12">
                                        {{ Form::jCancelSubmit(['submit-label' => 'Create', 'class' => '']) }}
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="modal_new_company" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content jform-container">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                    <h4 class="modal-title">New Company</h4>
                </div>

                {!!Form::open(['url' => '#', 'id' => 'createNewCompanyForm', 'class' => 'jform-form']) !!}
                    <div class="modal-body jform-body">
                        <div class="alert alert-error jform-errors-container hidden">
                            <span class="jform-errors-content"></span>
                            <span class="close"></span>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group form-row validation-field-container">
                                    <label class="field-label text-muted">Name</label>
                                    <label for="new_company_name" class="field prepend-icon">
                                        <input
                                            type="text"
                                            name="new_company_name"
                                            id="new_company_name"
                                            class="input-text full-width gui-input validation-field"
                                            placeholder="Company name"
                                            data-validator-required="true"
                                            data-validator-function="isPlainText"
                                            data-validator-message-required="This field is required."
                                            data-validator-message-error="Invalid entry.">
                                        <span class="field-icon"><i class="fa fa-bookmark"></i></span>
                                    </label>
                                    <span class="error-message"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-row validation-field-container">
                                    <label class="field-label text-muted">Category</label>
                                    <label for="new_company_category_id" class="field select">
                                        <select
                                            name="new_company_category_id"
                                            id="new_company_category_id"
                                            class="validation-field formcontrol"
                                            data-validator-required="true"
                                            data-validator-function="isPositive"
                                            data-validator-message-required="This field is required."
                                            data-validator-message-error="Select category.">
                                            @foreach ($companyCategoriesCB as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        <span class="field-icon"><i class="fa fa-bars"></i></span>
                                        <i class="arrow double"></i>
                                    </label>
                                    <span class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-sm-6 xs-hidden"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group form-row validation-field-container">
                                    <label class="field-label text-muted">Address</label>
                                    <label for="new_company_address" class="field prepend-icon">
                                        <input
                                            type="text"
                                            name="new_company_address"
                                            id="new_company_address"
                                            class="input-text full-width gui-input validation-field"
                                            placeholder="Company name"
                                            data-validator-required="true"
                                            data-validator-function="isPlainText"
                                            data-validator-message-required="This field is required."
                                            data-validator-message-error="Invalid entry.">
                                        <span class="field-icon"><i class="icon-location"></i></span>
                                    </label>
                                    <span class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-row validation-field-container">
                                    <label class="field-label text-muted">Address 2</label>
                                    <label for="new_company_address_2" class="field prepend-icon">
                                        <input
                                            type="text"
                                            name="new_company_address_2"
                                            id="new_company_address_2"
                                            class="input-text full-width gui-input validation-field"
                                            placeholder="Company name"
                                            data-validator-required="false"
                                            data-validator-function="isPlainText"
                                            data-validator-message-error="Invalid entry.">
                                        <span class="field-icon"><i class="icon-location"></i></span>
                                    </label>
                                    <span class="error-message"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group form-row validation-field-container">
                                    <label class="field-label text-muted">City</label>
                                    <label for="new_company_city" class="field prepend-icon">
                                        <input
                                            type="text"
                                            name="new_company_city"
                                            id="new_company_city"
                                            class="input-text full-width gui-input validation-field"
                                            placeholder="Company name"
                                            data-validator-required="true"
                                            data-validator-function="isPlainText"
                                            data-validator-message-required="This field is required."
                                            data-validator-message-error="Invalid entry.">
                                        <span class="field-icon"><i class="icon-building"></i></span>
                                    </label>
                                    <span class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group form-row validation-field-container">
                                    <label class="field-label text-muted">Zip Code</label>
                                    <label for="new_company_zipcode" class="field prepend-icon">
                                        <input
                                            type="text"
                                            name="new_company_zipcode"
                                            id="new_company_zipcode"
                                            class="input-text full-width gui-input validation-field"
                                            placeholder="Company name"
                                            data-validator-required="true"
                                            data-validator-function="isPlainText"
                                            data-validator-message-required="This field is required."
                                            data-validator-message-error="Invalid entry.">
                                        <span class="field-icon"><i class="fa fa-map-o"></i></span>
                                    </label>
                                    <span class="error-message"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-row validation-field-container">
                                    <label class="field-label text-muted">Country</label>
                                    <label for="new_company_country_id" class="field select">
                                        <select
                                            name="new_company_country_id"
                                            id="new_company_country_id"
                                            class="validation-field formcontrol"
                                            data-validator-required="true"
                                            data-validator-function="isPositive"
                                            data-validator-message-required="This field is required."
                                            data-validator-message-error="Select country.">
                                            @foreach ($countriesCB as $key => $value)
                                                <option value="{{ $key }}"{{ $key == 231 ? ' selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        <span class="field-icon"><i class="fa fa-globe"></i></span>
                                        <i class="arrow double"></i>
                                    </label>
                                    <span class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-row validation-field-container">
                                    <label class="field-label text-muted">State</label>
                                    <label for="new_company_state_id" class="field select">
                                        <select
                                            name="new_company_state_id"
                                            id="new_company_state_id"
                                            class="validation-field formcontrol"
                                            data-validator-required="true"
                                            data-validator-function="isPositive"
                                            data-validator-message-required="This field is required."
                                            data-validator-message-error="Select state.">
                                            @foreach ($statesCB as $key => $value)
                                                <option value="{{ $key }}"{{ $key == 3930 ? ' selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        <span class="field-icon"><i class="fa fa-map-pin"></i></span>
                                        <i class="arrow double"></i>
                                    </label>
                                    <span class="error-message"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button id="create_new_company_submit_button" type="button" class="btn btn-primary">Add Company</button>
                    </div>
                {!!Form::close() !!}
            </div>
        </div>
    </div>

    <div id="modal_new_manager" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content jform-container">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                    <h4 class="modal-title">New Manager</h4>
                </div>

                {!!Form::open(['url' => '#', 'id' => 'createNewManagerForm', 'class' => 'jform-form']) !!}
                    <div class="modal-body jform-body">
                        <div class="alert alert-error jform-errors-container hidden">
                            <span class="jform-errors-content"></span>
                            <span class="close"></span>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-row validation-field-container">
                                    <label class="field-label text-muted">First Name</label>
                                    <label for="new_manager_first_name" class="field prepend-icon">
                                        <input
                                            type="text"
                                            name="new_manager_first_name"
                                            id="new_manager_first_name"
                                            class="input-text full-width gui-input validation-field"
                                            placeholder=""
                                            data-validator-required="true"
                                            data-validator-function="isPersonName"
                                            data-validator-message-required="This field is required."
                                            data-validator-message-error="Invalid entry.">
                                        <span class="field-icon"><i class="fa fa-user"></i></span>
                                    </label>
                                    <span class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-row validation-field-container">
                                    <label class="field-label text-muted">Last Name</label>
                                    <label for="new_manager_last_name" class="field prepend-icon">
                                        <input
                                            type="text"
                                            name="new_manager_last_name"
                                            id="new_manager_last_name"
                                            class="input-text full-width gui-input validation-field"
                                            placeholder=""
                                            data-validator-required="true"
                                            data-validator-function="isPersonName"
                                            data-validator-message-required="This field is required."
                                            data-validator-message-error="Invalid entry.">
                                        <span class="field-icon"><i class="fa fa-user"></i></span>
                                    </label>
                                    <span class="error-message"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-row validation-field-container">
                                    <label class="field-label text-muted">Email</label>
                                    <label for="new_manager_email" class="field prepend-icon">
                                        <input
                                            type="text"
                                            name="new_manager_email"
                                            id="new_manager_email"
                                            class="input-text full-width gui-input validation-field"
                                            placeholder=""
                                            data-validator-required="false"
                                            data-validator-function="isEmail"
                                            data-validator-message-required="This field is required."
                                            data-validator-message-error="Invalid entry.">
                                        <span class="field-icon"><i class="fa fa-envelope"></i></span>
                                    </label>
                                    <span class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group form-row validation-field-container">
                                    <label class="field-label text-muted">Phone</label>
                                    <label for="new_manager_phone" class="field prepend-icon">
                                        <input
                                            type="text"
                                            name="new_manager_phone"
                                            id="new_manager_phone"
                                            class="input-text full-width gui-input validation-field"
                                            placeholder=""
                                            data-validator-required="true"
                                            data-validator-function="isPhone"
                                            data-validator-message-required="This field is required."
                                            data-validator-message-error="Invalid entry.">
                                        <span class="field-icon"><i class="fa fa-phone"></i></span>
                                    </label>
                                    <span class="error-message"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-row validation-field-container">
                                    <label class="field-label text-muted">Category</label>
                                    <label for="new_manager_category_id" class="field select">
                                        <select
                                            name="new_manager_category_id"
                                            id="new_manager_category_id"
                                            class="validation-field formcontrol"
                                            data-validator-required="true"
                                            data-validator-function="isPositive"
                                            data-validator-message-required="This field is required."
                                            data-validator-message-error="Select category.">
                                            @foreach ($contactCategoriesCB as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        <span class="field-icon"><i class="fa fa-bars"></i></span>
                                        <i class="arrow double"></i>
                                    </label>
                                    <span class="error-message"></span>
                                </div>
                            </div>
                            <div class="col-sm-6 xs-hidden"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button id="create_new_manager_submit_button" type="button" class="btn btn-primary">Add Manager</button>
                    </div>
                {!!Form::close() !!}
            </div>
        </div>
    </div>
@stop

@section('css-page-level-plugins')
    {!! Html::style($publicUrl . '/assets/global/plugins/select2/css/select2.min.css') !!}
    {!! Html::style($publicUrl . '/assets/global/plugins/select2/css/select2-bootstrap.min.css') !!}
@stop

@section('js-page-level-scripts')
    {!! Html::script($publicUrl .'/assets/global/plugins/select2/js/select2.full.min.js') !!}
@stop

@section('js-files')
    <script>
        var property_id = "{{ $property->id ?? '' }}";
        var owner_id = "{{ $property->owner_id ?? '' }}";
        var owner_name = "{{ $property->owner->fullName ?? '' }}";
        var company_id = "{{ $property->company_id ?? '' }}";
        var company_name = "{{ $property->company->name ?? '' }}";
        var manager_id = "{{ $property->manager_id ?? '' }}";
        var manager_name = "{{ $property->manager->fullName ?? '' }}";
        var manager_email = "{{ $property->manager->email ?? '' }}";
        var manager_phone = "{{ $property->manager->phone ?? '' }}";

        $(function(){
            $('#service_category_ids').multiselect({
                includeSelectAllOption: true
            });

            $('#how_did_you_hear_about_us').val('').multiselect();
            $('#how_did_you_hear_about_us').change(function(){
                if ($(this).val() == 'Referral') {
                    $('#referring_person_container').removeClass('hidden');
                } else {
                    $('#referring_person_container').addClass('hidden');
                    $('#referring_person').val('');
                }
            });


            var htmlContacsCB = {!! $json_html_contacts_cb !!};

            $('#new_owner_id').select2({
                data: htmlContacsCB,
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(data) {
                    return data.html;
                },
                templateSelection: function(data) {
                    return data.text;
                }
            });

            $('#new_owner_id').change(function(ev){
                if ($(this).val() > 0) {
                    $('#new_owner_id-error').remove();
                }
            });

            var htmlPropertiesCB = {!! $json_html_properties_cb !!};

            $('#new_property_id').select2({
                data: htmlPropertiesCB,
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(data) {
                    return data.html;
                },
                templateSelection: function(data) {
                    return data.text;
                }
            });

            $('#new_property_id').change(function(ev){
                if ($(this).val() > 0) {
                    $('#new_property_id-error').remove();
                }

                $('#data-container').addClass('hidden');

                $('#new_company_id').val('0').trigger('change.select2');

                resetContactsAndUserCompanyCBs();

                $.ajax({
                    type:"post",
                    url: "{{ route('ajax_property_details_fetch') }}",
                    data: {
                        property_id: $(this).val()
                    },
                    beforeSend: function (request){
                        PNotify.removeAll();
                        showSpinner();
                    },
                    complete: function(){
                        hideSpinner();
                    },
                    success: function(response){
                        if (response.success) {
                            $('#name').val(response.property.name);

                            var companyName = response.property.company != null ? response.property.company.name : '';
                            $('#show_company_name').text(companyName);

                            if (response.property.manager != null) {
                                var managerName = response.property.manager.first_name + ' ' + response.property.manager.last_name;
                                $('#show_manager_name').text(managerName);
                                $('#email').val(response.property.manager.email);
                                $('#phone').val(response.property.manager.phone);
                            }

                            //  show_property_owner_name new_owner_id

                            var propertyOwnerName = response.property.owner != null ? response.property.owner.first_name + ' ' + response.property.owner.last_name : '';
                            $('#show_property_owner_name').text(propertyOwnerName);

                            $('#new_owner_id').val(response.property.owner_id).trigger('change.select2');

                            // property_owner_show_container property_owners_container

                            $('#property_owner_show_container').addClass('hidden');
                            $('#property_owners_container .top-right-field-link').addClass('hidden');

                            $('#property_owners_container').removeClass('hidden');


                            $('#address').val(response.property.address);
                            $('#address_2').val(response.property.address_2);
                            $('#city').val(response.property.city);
                            $('#zipcode').val(response.property.zipcode);
                            $('#country_id').val(response.property.country_id);

                            $('#state_id').empty();
                            var html = Object.keys(response.states_cb).length > 0 ? ['<option value="0">Select State</option>'] : [];
                            $.each(response.states_cb, function(index, value){
                                html.push('<option value="'+ index +'">'+ value +'</option>')
                            });
                            $('#state_id').html(html.join(''));
                            $('#state_id').val(response.property.state_id);

                            if (response.property.company) {
                                $('#billing_address').val(response.property.company.billing_address);
                                $('#billing_address_2').val(response.property.company.billing_address_2);
                                $('#billing_city').val(response.property.company.billing_city);
                                $('#billing_zipcode').val(response.property.company.billing_zipcode);
                                $('#billing_country_id').val(response.property.company.billing_country_id);

                                $('#billing_state_id').empty();
                                var html = Object.keys(response.states_cb).length > 0 ? ['<option value="0">Select State</option>'] : [];
                                $.each(response.states_cb, function(index, value){
                                    html.push('<option value="'+ index +'">'+ value +'</option>')
                                });
                                $('#billing_state_id').html(html.join(''));
                                $('#billing_state_id').val(response.property.company.billing_state_id);
                            }

                            $('#company_show_container').removeClass('hidden');
                            $('#companies_container').addClass('hidden');

                            $('#company_manager_show_container').removeClass('hidden');
                            $('#company_users_container').addClass('hidden');
                            $('#contacts_container').addClass('hidden');

                            $('#data-container').removeClass('hidden');

                            $('#property_header').text('Property Details');

                            // set ids fields:

                            $('#property_id').val(response.property.id);
                            $('#company_id').val(response.property.company_id);
                            $('#manager_id').val(response.property.manager_id);

                            // if this is the first time a property is defined, set these global variables:
                            if (property_id == "") {
                                property_id = response.property.id;
                                company_id = response.property.company_id;
                                company_name = companyName;
                                manager_id = response.property.manager_id;

                                if (response.property.manager != null) {
                                    manager_name = managerName;
                                    manager_email = response.property.manager.email;
                                    manager_phone = response.property.manager.phone;
                                } else {
                                    manager_email = '';
                                    manager_phone = '';
                                }

                            }
                        } else {
                            pnAlert({
                                type: 'error',
                                title: 'Error',
                                text: response.message,
                                addClass: 'mt50'
                            });
                        }
                    }
                });
            });

            $('#new_company_id').change(function(ev){
                $.ajax({
                    type:"post",
                    url: "{{ route('ajax_company_details_fetch') }}",
                    data: {
                        company_id: $(this).val()
                    },
                    beforeSend: function (request){
                        PNotify.removeAll();
                        showSpinner();
                    },
                    complete: function(){
                        hideSpinner();
                    },
                    success: function(response){
                        if (response.success) {
                            $('#billing_address').val(response.company.billing_address);
                            $('#billing_address_2').val(response.company.billing_address_2);
                            $('#billing_city').val(response.company.billing_city);
                            $('#billing_zipcode').val(response.company.billing_zipcode);
                            $('#billing_country_id').val(response.company.billing_country_id);

                            $('#billing_state_id').empty();
                            var html = Object.keys(response.billing_states_cb).length > 0 ? ['<option value="0">Select State</option>'] : [];
                            $.each(response.billing_states_cb, function(index, value){
                                html.push('<option value="'+ index +'">'+ value +'</option>')
                            });
                            $('#billing_state_id').html(html.join(''));
                            $('#billing_state_id').val(response.company.billing_state_id);

                            $('#new_company_user_id').empty();

                            if (typeof response.company_users_cb.length == 'undefined') {  // means is an object and not empty array
                                var html = [];
                                $.each(response.company_users_cb, function(index, value){
                                    html.push('<option value="'+ index +'">'+ value +'</option>')
                                });
                                $('#new_company_user_id').html(html.join(''));
                                $('#new_company_user_id').prop("selectedIndex", manager_id); // try to select the original manager if the exists on the list

                                $('#company_manager_show_container').addClass('hidden');
                                $('#contacts_container').addClass('hidden');
                                $('#company_users_container').removeClass('hidden');
                            } else {
                                $('.company-users-link-container').addClass('hidden');

                                // show contacts list (as company usrs is empty):
                                $('#company_manager_show_container').addClass('hidden');
                                $('#contacts_container').removeClass('hidden');
                                $('#company_users_container').addClass('hidden');
                            }

                            $('#email').val('');
                            $('#phone').val('');

                            // set ids fields:

                            $('#company_id').val(response.company.id);
                            $('#manager_id').val($('#new_company_user_id').val());
                        } else {
                            pnAlert({
                                type: 'error',
                                title: 'Error',
                                text: response.message,
                                addClass: 'mt50'
                            });
                        }
                    }
                });
            });

            // property section

            $('#show_properties_list_link').click(function(ev){
                $('#properties_container').removeClass('hidden');
                $('#property_show_container').addClass('hidden');
            });
            $('#cancel_change_property_link').click(function(ev){
                $('#show_company_name').text(company_name);
                $('#company_id').val(company_id);

                // call cancel manager section:

                $('#new_property_id').val('0').trigger('change.select2');

                $('#properties_container').addClass('hidden');
                $('#property_show_container').removeClass('hidden');
            });

            $('#show_property_owner_list_link').click(function(ev){
                $('#new_owner_id').val(owner_id).trigger('change.select2');

                $('#property_owners_container').removeClass('hidden');
                $('#property_owner_show_container').addClass('hidden');
            });

            $('#cancel_change_property_owner_link').click(function(ev){
                $('#new_owner_id').val('0').trigger('change.select2');

                $('#show_property_owner_name').text(owner_name);
                $('#owner_id').val(owner_id);

                $('#property_owner_show_container').removeClass('hidden');
                $('#property_owners_container').addClass('hidden');
            });

            // company section

            $('#show_companies_list_link').click(function(ev){
                $('#companies_container').removeClass('hidden');
                $('#company_show_container').addClass('hidden');
            });

            $('#cancel_change_company_link').click(function(ev){
                resetCompaniesAndManagers();
            });

            $('.new-company-link').click(function(ev){
                $('#modal_new_company').modal('show');
            });

            $('#modal_new_company').on('shown.bs.modal', function(){
                resetJFormInputs($('#createNewCompanyForm'));

                $(this).find('.jform-errors-content').html('');
                $(this).find('.jform-errors-container').addClass('hidden');

                $('#new_company_category_id').val('');
                $('#new_company_country_id').val(231);
                $('#new_company_state_id').val(3930);
            });

            $('#create_new_company_submit_button').click(function(){
                var form = $('#createNewCompanyForm');

                if (! validateJForm(form)) {
                    return false;
                }

                $.ajax({
                    url : "{{ route('ajax_company_create') }}",
                    type: 'post',
                    data: form.serialize(),
                    beforeSend: function (request){
                        form.find('.jform-errors-content').html('');
                        form.find('.jform-errors-container').addClass('hidden');

                        showSpinner();
                    },
                    success: function(response) {
                        if (response.success) {

                            $('.company-users-link-container').addClass('hidden');

                            $('#billing_address').val(response.company.billing_address);
                            $('#billing_address_2').val(response.company.billing_address_2);
                            $('#billing_city').val(response.company.billing_city);
                            $('#billing_zipcode').val(response.company.billing_zipcode);
                            $('#billing_country_id').val(response.company.billing_country_id);

                            $('#billing_state_id').empty();
                            var html = Object.keys(response.billing_states_cb).length > 0 ? ['<option value="0">Select State</option>'] : [];
                            $.each(response.billing_states_cb, function(index, value){
                                html.push('<option value="'+ index +'">'+ value +'</option>')
                            });
                            $('#billing_state_id').html(html.join(''));
                            $('#billing_state_id').val(response.company.billing_state_id);

                            // make company users combobox empty:
                            $('#new_company_user_id').empty();

                            // hide company users link
                            $('.company-users-link-container').addClass('hidden');

                            // show contacts list (as company users list is empty):
                            $('#company_manager_show_container').addClass('hidden');
                            $('#contacts_container').removeClass('hidden');
                            $('#company_users_container').addClass('hidden');

                            // reset companies and managers:

                            resetCompaniesAndManagers();

                            // set new company values:

                            $('#company_id').val(response.company.id);
                            $('#show_company_name').text(response.company.name);

                            // set new company manager values:

                            $('#manager_id').val('');
                            $('#show_manager_name').text('');

                            // show contact list and hide other two:

                            $('#company_manager_show_container').addClass('hidden');
                            $('#company_users_container').addClass('hidden');
                            $('#contacts_container').removeClass('hidden');

                            // hide company links
                            $('.company-row .top-right-field-link').hide();

                            // hide reset manager link as it is no longer needed:

                            $('.manager-row #contacts_container .reset-link-container').hide();

                            hideSpinner();

                            $('#modal_new_company').modal('hide');
                        } else {
                            hideSpinner();

                            form.find('.jform-errors-content').html(response.message);
                            form.find('.jform-errors-container').removeClass('hidden');
                        }
                    },
                    error: function(data) {
                        hideSpinner();
                        console.log(data);
                    }
                });
            });

            // new_contact_id new_company_user_id email phone

            // managers section

            $('#show_company_users_from_show_manager_link').click(function(ev){
                resetContactsAndUserCompanyCBs();

                $('#company_users_container').removeClass('hidden');

                $('#contacts_container').addClass('hidden');
                $('#company_manager_show_container').addClass('hidden');
            });

            $('#show_company_users_from_contacts_link').click(function(ev){
                resetContactsAndUserCompanyCBs();

                $('#company_users_container').removeClass('hidden');

                $('#contacts_container').addClass('hidden');
                $('#company_manager_show_container').addClass('hidden');
            });

            $('#show_contacts_list_fom_show_manager_link').click(function(ev){
                resetContactsAndUserCompanyCBs();

                $('#contacts_container').removeClass('hidden');

                $('#company_users_container').addClass('hidden');
                $('#company_manager_show_container').addClass('hidden');
            });

            $('#show_contacts_list_from_company_users_link').click(function(ev){
                resetContactsAndUserCompanyCBs();

                $('#contacts_container').removeClass('hidden');

                $('#company_users_container').addClass('hidden');
                $('#company_manager_show_container').addClass('hidden');
            });

            $('.cancel-change-manager-link').click(function(ev){
                resetManagers();
            });

            $('#new_owner_id').change(function(ev){
                $('#owner_id').val($(this).val());
            });

            // new_company_user_id new_contact_id

            $('.manager-row').on('click', '.top-right-field-link a', function(){
                if ($(this).hasClass('reset')) {
                    $('#email').val(manager_email);
                    $('#phone').val(manager_phone);
                } else {
                    $('#email').val('');
                    $('#phone').val('');
                }
            });

            $('.manager-row').on('change', 'select', function(){
                $.ajax({
                    url : "{{ route('ajax_user_email_and_phone_fetch') }}",
                    type: 'post',
                    data: {
                        user_id: $(this).val()
                    },
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function(){
                        hideSpinner();
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#email').val(response.email);
                            $('#phone').val(response.phone);
                        }
                    },
                    error: function(data) {
                        hideSpinner();
                        console.log(data);
                    }
                });
            });

            $('#new_company_user_id').change(function(ev){
                $('#manager_id').val($(this).val());

                $('#email').val('');
                $('#phone').val('');
            });

            $('#new_contact_id').change(function(ev){
                $('#manager_id').val($(this).val());

                $('#email').val('');
                $('#phone').val('');
            });

            $('.new-manager-link').click(function(ev){
                $('#modal_new_manager').modal('show');
            });

            $('#modal_new_manager').on('shown.bs.modal', function(){
                resetJFormInputs($('#createNewManagerForm'));

                $(this).find('.jform-errors-content').html('');
                $(this).find('.jform-errors-container').addClass('hidden');

                $('#new_manager_category_id').val('');
            });

            $('#create_new_manager_submit_button').click(function(){
                var form = $('#createNewManagerForm');

                if (! validateJForm(form)) {
                    return false;
                }

                $.ajax({
                    url : "{{ route('ajax_contact_store') }}",
                    type: 'post',
                    data: form.serialize(),
                    beforeSend: function (request){
                        form.find('.jform-errors-content').html('');
                        form.find('.jform-errors-container').addClass('hidden');

                        showSpinner();
                    },
                    success: function(response) {
                        if (response.success) {
                            // reset managers:

                            resetManagers();

                            // set new manager values:

                            $('#manager_id').val(response.manager.id);
                            $('#show_manager_name').text(response.manager.first_name + ' ' + response.manager.last_name);
                            $('#email').val(response.manager.email);
                            $('#phone').val(response.manager.phone);

                            // hide manager links
                            $('.manager-row .top-right-field-link').hide();

                            hideSpinner();

                            $('#modal_new_manager').modal('hide');
                        } else {
                            hideSpinner();

                            form.find('.jform-errors-content').html(response.message);
                            form.find('.jform-errors-container').removeClass('hidden');
                        }
                    },
                    error: function(data) {
                        hideSpinner();
                        console.log(data);
                    }
                });
            });

            var htmlCompaniesCB = {!! $json_html_property_management_companies_cb !!};

            $('#new_company_id').select2({
                data: htmlCompaniesCB,
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(data) {
                    return data.html;
                },
                templateSelection: function(data) {
                    return data.text;
                }
            });

            $('body').on('change', '#new_company_id', function(ev){
                if ($(this).val() > 0) {
                    $('#new_company_id-error').remove();
                }
            });

            var htmlContactsCB = {!! $json_html_contacts_cb !!};

            $('#new_contact_id').select2({
                data: htmlContactsCB,
                escapeMarkup: function(markup) {
                    return markup;
                },
                templateResult: function(data) {
                    return data.html;
                },
                templateSelection: function(data) {
                    return data.text;
                }
            });

            $('body').on('click', '#new_contact_id', function(ev){
                if ($(this).val() > 0) {
                    $('#new_contact_id-error').remove();
                }
            });

            $('#createIntakeForm').validate({
                rules: {
                    name: {
                        required : true,
                        plainText: false
                    },
                    company_id: {
                        required: true,
                        positive: true
                    },
                    manager_id: {
                        required: true,
                        positive: true
                    },
                    // non required fields
                    email: {
                        required: true,
                        email   : true
                    },
                    phone: {
                        required: false,
                        phone   : true
                    },
                    address: {
                        required : false,
                        plainText: true
                    },
                    address_2: {
                        required : false,
                        plainText: true
                    },
                    city: {
                        required : false,
                        plainText: true
                    },
                    zipcode: {
                        required : false,
                        plainText: true
                    },
                    state_id: {
                        required      : false,
                        zeroOrPositive: true
                    },
                    country_id: {
                        required      : false,
                        zeroOrPositive: true
                    },
                    parcel_number: {
                        required : false,
                        plainText: true
                    },
                    comment: {
                        required : false,
                        plainText: true
                    },
                    what_services_were_you_looking_for: {
                        required : false,
                        plainText: true
                    },
                    how_did_you_hear_about_us: {
                        required : false,
                        plainText: true
                    },
                    referring_person: {
                        required  : false,
                        personName: true
                    },
                    event_started_at: {
                        required  : false,
                        usDateTime: true
                    },
                    event_ended_at: {
                        required  : false,
                        usDateTime: true
                    },
                    event_name: {
                        required : false,
                        plainText: true
                    },
                    event_description: {
                        required : false,
                        plainText: true
                    }
                },
                messages: {
                    company_id: {
                        required: 'Please, select a company',
                        positive: 'Please, select a company'
                    },
                    manager_id: {
                        required: 'Please, select a manager',
                        positive: 'Please, select a manager'
                    }
                },
                submitHandler: function(form) {
                    showSpinner();

                    form.submit();
                }
            });

            $('#sales_manager_id').change(function(ev){
                if ($(this).val() == 0) {
                    $('#sales_person_container').addClass('hidden');
                    $('#create_appointment_container').addClass('hidden');

                    $('#event_started_at').val('');
                    $('#event_ended_at').val('');
                    $('#event_name').val('');
                    $('#event_description').val('');

                    $('#sales_person_id').val(0);
                } else {
                    $('#sales_person_container').removeClass('hidden');
                    $('#create_appointment_container').removeClass('hidden');
                }
            });

            $('#country_id').change(function(ev){
                $.ajax({
                    type:"post",
                    url: "{{ route('ajax_state_fetch') }}",
                    data: {
                        country_id: $(this).val()
                    },
                    beforeSend: function (request){
                        PNotify.removeAll();
                        showSpinner();
                    },
                    complete: function(){
                        hideSpinner();
                    },
                    success: function(response){
                        $('#state_id').empty();
                        if (response.success) {
                            var html = Object.keys(response.data).length > 0 ? ['<option value="0">Select state</option>'] : [];
                            $.each(response.data, function(index, value){
                                html.push('<option value="'+ index +'">'+ value +'</option>')
                            });
                            $('#state_id').html(html.join(''));
                        } else {
                            pnAlert({
                                type: 'error',
                                title: 'Error',
                                text: response.message,
                                addClass: 'mt50'
                            });
                        }
                    }
                });
            });

            $('#billing_country_id').change(function(ev){
                $.ajax({
                    type:"post",
                    url: "{{ route('ajax_state_fetch') }}",
                    data: {
                        country_id: $(this).val()
                    },
                    beforeSend: function (request){
                        PNotify.removeAll();
                        showSpinner();
                    },
                    complete: function(){
                        hideSpinner();
                    },
                    success: function(response){
                        $('#billing_state_id').empty();
                        if (response.success) {
                            var html = Object.keys(response.data).length > 0 ? ['<option value="0">Select state</option>'] : [];
                            $.each(response.data, function(index, value){
                                html.push('<option value="'+ index +'">'+ value +'</option>')
                            });
                            $('#billing_state_id').html(html.join(''));
                        } else {
                            pnAlert({
                                type: 'error',
                                title: 'Error',
                                text: response.message,
                                addClass: 'mt50'
                            });
                        }
                    }
                });
            });

            $('#new_company_country_id').change(function(ev){
                $.ajax({
                    type:"post",
                    url: "{{ route('ajax_state_fetch') }}",
                    data: {
                        country_id: $(this).val()
                    },
                    beforeSend: function (request){
                        PNotify.removeAll();
                        showSpinner();
                    },
                    complete: function(){
                        hideSpinner();
                    },
                    success: function(response){
                        $('#new_company_state_id').empty();
                        if (response.success) {
                            var html = Object.keys(response.data).length > 0 ? ['<option value="0">Select state</option>'] : [];
                            $.each(response.data, function(index, value){
                                html.push('<option value="'+ index +'">'+ value +'</option>')
                            });
                            $('#new_company_state_id').html(html.join(''));
                        } else {
                            pnAlert({
                                type: 'error',
                                title: 'Error',
                                text: response.message,
                                addClass: 'mt50'
                            });
                        }
                    }
                });
            });

            $('#cancel-button').click(function(ev){
                ev.preventDefault();
                window.location = "{{ route('dashboard') }}";
            });
        });
        function resetContactsAndUserCompanyCBs()
        {
            $('#new_company_user_id').val('');
            $('#new_contact_id').val('0').trigger('change.select2');

            $('#email').val(manager_email);
            $('#phone').val(manager_phone);

        }
        function resetCompaniesAndManagers()
        {
            $('#show_company_name').text(company_name);
            $('#company_id').val(company_id);

            $('#new_company_id').val('0').trigger('change.select2');

            $('#companies_container').addClass('hidden');
            $('#company_show_container').removeClass('hidden');

            resetManagers();
        }
        function resetManagers()
        {
            $('#show_manager_name').text(manager_name);
            $('#manager_id').val(manager_id);

            $('#company_manager_show_container').removeClass('hidden');
            $('#company_users_container').addClass('hidden');
            $('#contacts_container').addClass('hidden');

            resetContactsAndUserCompanyCBs();
        }
    </script>
@stop
@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><a href="{{ route('property_list') }}">Properties</a><i class="fa fa-angle-right"></i></li>
            <li><span>Create New</span></li>
        </ul>
    </div>
@stop

@section('content')
    <section id="content" class="animated fadeIn list-items admin-form">
        <div class="row">
            <div class="col-md-9 center-block">
                @include('errors._list')
                <div class="admin-form theme-primary">
                    <div class="panel">
                        {!! Form::open(['url' => route('property_store'), 'id' => 'createForm']) !!}
                            {!! Form::hidden('returnTo', $returnTo) !!}
                            @include('property._form', ['formTitle' => 'New Property', 'submitButtonText' => 'Create Property', 'create' => true])
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="modal_new_owner" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content jform-container">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                    <h4 class="modal-title">New Property Owner</h4>
                </div>

                {!!Form::open(['url' => '#', 'id' => 'createNewOwnerForm', 'class' => 'jform-form']) !!}
                    {!! Form::hidden('new_owner_category_id', 16) !!}  <!-- property owner -->
                    <div class="modal-body jform-body">
                        <div class="alert alert-error jform-errors-container hidden">
                            <span class="jform-errors-content"></span>
                            <span class="close"></span>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-row validation-field-container">
                                    <label class="field-label text-muted">First Name</label>
                                    <label for="new_owner_first_name" class="field prepend-icon">
                                        <input
                                                type="text"
                                                name="new_owner_first_name"
                                                id="new_owner_first_name"
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
                                    <label for="new_owner_last_name" class="field prepend-icon">
                                        <input
                                                type="text"
                                                name="new_owner_last_name"
                                                id="new_owner_last_name"
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
                                    <label for="new_owner_email" class="field prepend-icon">
                                        <input
                                                type="text"
                                                name="new_owner_email"
                                                id="new_owner_email"
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
                                    <label for="new_owner_phone" class="field prepend-icon">
                                        <input
                                                type="text"
                                                name="new_owner_phone"
                                                id="new_owner_phone"
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button id="create_new_owner_submit_button" type="button" class="btn btn-primary">Add Owner</button>
                    </div>
                {!!Form::close() !!}
            </div>
        </div>
    </div>

    <div id="modal_new_company" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content jform-container">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                    <h4 class="modal-title">New Management Company</h4>
                </div>

                {!!Form::open(['url' => '#', 'id' => 'createNewCompanyForm', 'class' => 'jform-form']) !!}
                    {!! Form::hidden('new_company_category_id', 7) !!}  <!-- property Management Company -->
                    <div class="modal-body jform-body">
                        <div class="alert alert-error jform-errors-container hidden">
                            <span class="jform-errors-content"></span>
                            <span class="close"></span>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group form-row validation-field-container m-b-5">
                                    <label class="field-label text-muted">Company Name</label>
                                    <label for="new_company_name" class="field prepend-icon">
                                        <input
                                                type="text"
                                                name="new_company_name"
                                                id="new_company_name"
                                                class="input-text full-width gui-input validation-field"
                                                placeholder="Enter Company Name"
                                                data-validator-required="true"
                                                data-validator-function="isPlainText"
                                                data-validator-message-required="This field is required."
                                                data-validator-message-error="Invalid entry.">
                                        <span class="field-icon"><i class="fa fa-bookmark"></i></span>
                                    </label>
                                    <span class="error-message"></span>
                                </div>
								
                            </div>
							<div class="col-sm-6">
									<div class="form-group form-row validation-field-container">
										<label class="field-label text-muted">Address</label>
										<label for="new_company_address" class="field prepend-icon">
											<input
													type="text"
													name="new_company_address"
													id="new_company_address"
													class="input-text full-width gui-input validation-field"
													placeholder="Enter Address"
													data-validator-required="true"
													data-validator-function="isPlainText"
													data-validator-message-required="This field is required."
													data-validator-message-error="Invalid entry.">
											<span class="field-icon"><i class="icon-location"></i></span>
										</label>
										<span class="error-message"></span>
									</div>
								</div>
                        </div>
                        
                        <div class="row">
							<div class="col-sm-4">
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
                            <div class="col-sm-4">
                                <div class="form-group form-row validation-field-container">
                                    <label class="field-label text-muted">City</label>
                                    <label for="new_company_city" class="field prepend-icon">
                                        <input
                                                type="text"
                                                name="new_company_city"
                                                id="new_company_city"
                                                class="input-text full-width gui-input validation-field"
                                                placeholder="Enter City"
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
                                                placeholder="Enter Zipcode"
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
                    <h4 class="modal-title">New Management Contact</h4>
                </div>

                {!!Form::open(['url' => '#', 'id' => 'createNewManagerForm', 'class' => 'jform-form']) !!}
                    {!! Form::hidden('new_manager_category_id', 12) !!}  <!-- property manager -->
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
        $(function(){
            var htmlPropertyOwnersCB = {!! $json_html_property_owners_cb !!};

            $('#owner_id').select2({
                data: htmlPropertyOwnersCB,
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

            var htmlCompaniesCB = {!! $json_html_property_management_companies_cb !!};

            $('#company_id').select2({
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

            var htmlContactsCB = {!! $json_html_contacts_cb !!};

            $('#company_manager_id').select2({
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

            
            // new owner

            $('#owner_id').change(function(ev){
                if ($(this).val() > 0) {
                    $('#owner_id-error').remove();
                }
            });

            $('.new-owner-link').click(function(ev){
                $('#modal_new_owner').modal('show');
            });

            $('#modal_new_owner').on('shown.bs.modal', function(){
                resetJFormInputs($('#createNewOwnerForm'));

                $(this).find('.jform-errors-content').html('');
                $(this).find('.jform-errors-container').addClass('hidden');
            });

            $('#create_new_owner_submit_button').click(function(){
                var form = $('#createNewOwnerForm');

                if (! validateJForm(form)) {
                    return false;
                }

                $.ajax({
                    url : "{{ route('ajax_property_new_owner_store') }}",
                    type: 'post',
                    data: form.serialize(),
                    beforeSend: function (request){
                        form.find('.jform-errors-content').html('');
                        form.find('.jform-errors-container').addClass('hidden');

                        showSpinner();
                    },
                    complete: function(){
                        hideSpinner();
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#owner_show_container').append('<input type="hidden" name="owner_id" value="'+ response.owner.id +'">');
                            $('#show_owner_name').html(response.owner.first_name + ' ' + response.owner.last_name);

                            $('#owner_select2_container').remove();
                            $('#new_owner_link_container').remove();
                            $('#owner_show_container').removeClass('hidden');

                            $('#modal_new_owner').modal('hide');
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


            // new company

            $('.new-company-link').click(function(ev){
                $('#modal_new_company').modal('show');
            });

            $('#modal_new_company').on('shown.bs.modal', function(){
                resetJFormInputs($('#createNewCompanyForm'));

                $(this).find('.jform-errors-content').html('');
                $(this).find('.jform-errors-container').addClass('hidden');

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
                    complete: function(){
                        hideSpinner();
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#company_show_container').append('<input type="hidden" id="company_id" name="company_id" value="'+ response.company.id +'">');
                            $('#show_company_name').html(response.company.name);

                            $('#new_company_link_container').remove();
                            $('#company_select2_container').remove();
                            $('#company_show_container').removeClass('hidden');

                            $('#manager_select_container').remove();
                            $('#manager_select2_container').removeClass('hidden');

                            $('#new_manager_link_container').removeClass('hidden');  // as company_id has value now

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


            // new manager

            $('.new-manager-link').click(function(ev){
                $('#modal_new_manager').modal('show');
            });

            $('#modal_new_manager').on('shown.bs.modal', function(){
                resetJFormInputs($('#createNewManagerForm'));

                $(this).find('.jform-errors-content').html('');
                $(this).find('.jform-errors-container').addClass('hidden');
            });

            $('body').on('click', '#contact_manager_id', function(ev){
                if ($(this).val() > 0) {
                    $('#contact_manager_id-error').remove();

                    $('#manager_id').val($(this).val());
                }
            });
            $('body').on('click', '#company_manager_id', function(ev){
                if ($(this).val() > 0) {
                    $('#company_manager_id-error').remove();

                    $('#manager_id').val($(this).val());
                }
            });


            $('#create_new_manager_submit_button').click(function(){
                var form = $('#createNewManagerForm');

                if (! validateJForm(form)) {
                    return false;
                }

                var formData = $('#createNewManagerForm').serializeObject();

                $.extend(formData, {
                    company_id: $('#company_id').val(),
                    company_position_id: 2                      // manager
                });

                $.ajax({
                    url : "{{ route('ajax_contact_store') }}",
                    type: 'post',
                    //data: form.serialize(),
                    data: formData,
                    beforeSend: function (request){
                        form.find('.jform-errors-content').html('');
                        form.find('.jform-errors-container').addClass('hidden');

                        showSpinner();
                    },
                    complete: function(){
                        hideSpinner();
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#manager_id').val(response.manager.id);

                            $('#show_manager_name').html(response.manager.first_name + ' ' + response.manager.last_name);

                            $('#new_manager_link_container').remove();
                            $('#manager_select2_container').remove();
                            $('#manager_show_container').removeClass('hidden');

                            $('#manager_select_container').addClass('hidden');

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

            $('#createForm').validate({
                rules: {
                    name: {
                        required : true,
                        plainText: false
                    },
                    address: {
                        required : true,
                        plainText: true
                    },
                    city: {
                        required : true,
                        plainText: true
                    },
                    zipcode: {
                        required : true,
                        plainText: true
                    },
                    // non required fields
                    state_id: {
                        required      : false,
                        zeroOrPositive: true
                    },
                    country_id: {
                        required      : false,
                        zeroOrPositive: true
                    },
                    owner_id: {
                        required      : true,
                        zeroOrPositive: true
                    },
                    company_id: {
                        required      : true,
                        zeroOrPositive: true
                    },
                    manager_id: {
                        required     : true,
                        zeroOrPositive: true
                    },
                    email: {
                        required: false,
                        email   : true
                    },
                    phone: {
                        required: false,
                        phone   : true
                    },
                    address_2: {
                        required : false,
                        plainText: true
                    },
                    parcel_number: {
                        required : false,
                        plainText: true
                    },
                    comment: {
                        required : false,
                        plainText: true
                    }
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

            /*$('#company_id').change(function(ev){

                if ($(this).val() > 0) {
                    $('#company_id-error').remove();
                }

                $.ajax({
                    type:"post",
                    url: "{{ route('ajax_company_users_fetch') }}",
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
                        $('#company_manager_id').empty();
                        $('#manager_id').val('');

                        if (response.success) {
                            var html = Object.keys(response.data).length > 0 ? ['<option value="0">Select manager</option>'] : [];
                            $.each(response.data, function(index, value){
                                html.push('<option value="'+ index +'">'+ value +'</option>')
                            });

                            $('#company_manager_id').html(html.join(''));

                            $('#new_manager_link_container').removeClass('hidden');  // as company_id has value now

                            $('#manager_show_container').addClass('hidden');
                            $('#manager_select_container').removeClass('hidden');
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
            });*/

            $('#cancel-button').click(function(ev){
                ev.preventDefault();
                window.location = "{{ route('property_list') }}";
            });
        });
    </script>
@stop
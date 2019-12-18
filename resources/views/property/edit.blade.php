@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><a href="{{ route('property_list') }}">Properties</a><i class="fa fa-angle-right"></i></li>
            <li><span>Edit</span></li>
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
                        {!! Form::model($property, ['url' => route('property_update', ['id' => $property->id]), 'method' => 'PATCH', 'id' => 'updateForm']) !!}
                            {!! Form::hidden('id', $property->id) !!}
                            @include('property._form', ['formTitle' => 'Update Property', 'submitButtonText' => 'Update'])
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
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

            $('#company_id').val("{{ $property->company_id }}").trigger('change.select2');

            $('#company_id').change(function(ev){
                if ($(this).val() > 0) {
                    $('#company_id-error').remove();
                }
            });

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

            $('#owner_id').change(function(ev){
                if ($(this).val() > 0) {
                    $('#owner_id-error').remove();
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
            $('#updateForm').validate({
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
                        required      : false,
                        zeroOrPositive: true
                    },
                    company_id: {
                        required      : false,
                        zeroOrPositive: true
                    },
                    manager_id: {
                        required     : false,
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
                        $('#manager_id').empty();
                        if (response.success) {
                            var html = Object.keys(response.data).length > 0 ? ['<option value="0">Select manager</option>'] : [];
                            $.each(response.data, function(index, value){
                                html.push('<option value="'+ index +'">'+ value +'</option>')
                            });
                            $('#manager_id').html(html.join(''));
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
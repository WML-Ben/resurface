@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><a href="{{ route('company_list') }}">Companies</a><i class="fa fa-angle-right"></i></li>
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
                        {!! Form::open(['url' => route('company_store'), 'id' => 'createForm']) !!}
                            @include('company._form', ['formTitle' => 'New Company', 'submitButtonText' => 'Create Company', 'create' => true])
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('js-files')
    <script>
        $(function(){
            $('#createForm').validate({
                rules: {
                    name: {
                        required : true,
                        plainText: false
                    },
                    category_id: {
                        required: true,
                        positive: true
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
                    email: {
                        required: false,
                        email   : true
                    },
                    phone: {
                        required: false,
                        phone   : true
                    },
                    alt_email: {
                        required: false,
                        email   : true
                    },
                    alt_phone: {
                        required: false,
                        phone   : true
                    },
                    address_2: {
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
                    billing_address: {
                        required : false,
                        plainText: true
                    },
                    billing_address_2: {
                        required : false,
                        plainText: true
                    },
                    billing_city: {
                        required : false,
                        plainText: true
                    },
                    billing_zipcode: {
                        required : false,
                        plainText: true
                    },
                    billing_state_id: {
                        required      : false,
                        zeroOrPositive: true
                    },
                    billing_country_id: {
                        required      : false,
                        zeroOrPositive: true
                    },
                    comment: {
                        required : false,
                        plainText: true
                    }
                },
                messages: {
                    category_id: {
                        required: 'Please, select a category',
                        positive: 'Please, select a category'
                    },
                    state_id: {
                        required: 'Please, select a state',
                        positive: 'Please, select a state'
                    },
                    country_id: {
                        required: 'Please, select a country',
                        positive: 'Please, select a country'
                    },
                    billing_state_id: {
                        positive: 'Please, select a state'
                    }
                }
            });

            if ($('#above_as_billing_address').is(':checked')) {
                $('#billing_container').hide().find('input,select').val('');
            }

            $('#above_as_billing_address').change(function(ev){
                if ($(this).is(':checked')) {
                    $('#billing_container').hide().find('input,select').val('');
                } else {
                    $('#billing_container').show()
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

            $('#cancel-button').click(function(ev){
                ev.preventDefault();
                window.location = "{{ route('company_list') }}";
            });
        });
    </script>
@stop
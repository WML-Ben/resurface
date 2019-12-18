@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><a href="{{ route('employee_list') }}">Employees</a><i class="fa fa-angle-right"></i></li>
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
                        {!! Form::model($employee, ['url' => route('employee_update', ['id' => $employee->id]), 'method' => 'PATCH', 'id' => 'updateForm']) !!}
                            {!! Form::hidden('id', $employee->id) !!}
                            @include('employee._form', ['formTitle' => 'Update Employee', 'roleId' => $employee->role_id, 'submitButtonText' => 'Update'])
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
            $('#updateForm').validate({
                rules: {
                    first_name: {
                        required  : true,
                        firstName: true
                    },
                    last_name: {
                        required: true,
                        lastName: true
                    },
                    email: {
                        required: true,
                        email   : true
                    },
                    role_id: {
                        required: true,
                        positive : true
                    },
                    phone: {
                        required: true,
                        phone   : true
                    },
                    // non required fields:
                    password:{
                        required : false,
                        password : true,
                        minlength: 6
                    },
                    repeat_password:{
                        required : false,
                        password : true,
                        equalTo  : '#password',
                        minlength: 6
                    },
                    salutation: {
                        required : false,
                        plainText: true,
                        maxlength: 10
                    },
                    middle_name: {
                        required : false,
                        firstName: true
                    },
                    alt_phone: {
                        required: false,
                        phone   : true
                    },
                    title: {
                        required : false,
                        plainText: true
                    },
                    alt_email: {
                        required: false,
                        email   : true
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
                    date_of_birth: {
                        required: false,
                        usDate  : true
                    },
                    hired_at: {
                        required: false,
                        usDate  : true
                    },
                    comment: {
                        required : false,
                        plainText: true
                    }
                },
                messages: {
                    password: {
                        minlength: 'Password should have at least {0} characters.'
                    },
                    repeat_password: 'Passwords don\'t match.',
                    role_id: {
                        required: 'Please, select a role.',
                        positive: 'Please, select a role.'
                    },
                    salutation: {
                        maxlength: 'Max. length: {0}.'
                    }
                }
            });

            $('.dropzone').html5imageupload({
                onAfterSelectImage: function() {
                    resetError($(this.element));
                },
                onAfterProcessImage: function() {
                    $('#' + $(this.element).data('hidden-field-id')).val($(this.element).data('newFileName'));
                    resetError($(this.element));
                },
                onAfterCancel: function() {
                    setError($(this.element));
                },
                onAfterRemoveImage: function() {
                    setError($(this.element));
                }
            });

            $('#cancel-button').click(function(ev){
                ev.preventDefault();
                window.location = "{{ route('employee_list') }}";
            });

            $('#submit-button').click(function(ev){
                if (!$('#avatar').val() && $('#old_avatar').val()) {
                    ev.preventDefault();
                    uiAlert({type: 'error', text: 'The employee\'s picture is missing or not saved.'});
                }
                if (!$('#signature').val() && $('#old_signature').val()) {
                    ev.preventDefault();
                    uiAlert({type: 'error', text: 'The employee\'s signature is missing or not saved.'});
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
        });
        function resetError(el)
        {
            el.next('em.state-error').remove();
            el.removeClass('state-error');
        }
        function setError(el)
        {
            if (el.hasClass('user')) {
                $('#avatar').val('');
                $('#avatarFileField').val('');

                if ($('#avatarFileField-error').size() == 0) {
                    el.after('<em class="state-error avatar-error" id="avatarFileField-error">This field is required.</em>');
                }
            }

            if (el.hasClass('signature')) {
                $('#signature').val('');
                $('#signatureFileField').val('');

                if ($('#signatureFileField-error').size() == 0) {
                    el.after('<em class="state-error signature-error" id="signatureFileField-error">This field is required.</em>');
                }
            }

            if (!el.hasClass('state-error')) {
                el.addClass('state-error');
            }
        }
    </script>
@stop
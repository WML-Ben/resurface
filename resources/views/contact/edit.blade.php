@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><a href="{{ route('contact_list') }}">Contacts</a><i class="fa fa-angle-right"></i></li>
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
                        {!! Form::model($contact, ['url' => route('contact_update', ['id' => $contact->id]), 'method' => 'PATCH', 'id' => 'updateForm']) !!}
                            {!! Form::hidden('id', $contact->id) !!}
                            @include('contact._form', ['formTitle' => 'Update Contact', 'submitButtonText' => 'Update Contact'])
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

            $('#company_id').val("{{ $contact->company_id }}").trigger('change.select2');

            $('#company_id').change(function(ev){
                if ($(this).val() > 0) {
                    $('#company_id-error').remove();
                }
            });

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
                    category_id: {
                        required: true,
                        positive: true
                    },
                    // non required fields:
                    email: {
                        required: false,
                        email   : true
                    },
                    company_id: {
                        required      : false,
                        zeroOrPositive: true
                    },
                    overhead: {
                        required: false,
                        positive: true
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
                    phone: {
                        required: false,
                        phone   : true
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
                    comment: {
                        required : false,
                        plainText: true
                    }
                },
                messages: {
                    company_id: {
                        required: 'Please, select a company',
                        positive: 'Please, select a company'
                    },
                    category_id: {
                        required: 'Please, select a category',
                        positive: 'Please, select a category'
                    },
                    salutation: {
                        maxlength: 'Max. length: {0}.'
                    }
                }
            });

            // conditional rule:    overhead required if category_id = 11 - Sub Contractor

            $('#category_id').change(function(ev){
                var container = $('#overhead').parents('label.field');

                container.removeClass('state-error');

                if ($('#overhead-error')){
                    $('#overhead-error').remove();
                }

                if ($(this).val() == 11) {
                    $('#overhead').rules('remove');
                    $('#overhead').rules('add', {
                        required: true,
                        positive: true,             // define validation rule for overhead ("positive")
                        messages: {
                            required: 'This field is required.',
                            positive: jQuery.validator.format('Overhead must be a positive number.')
                        }
                    });
                } else {
                    $('#overhead').rules('remove');
                    $('#overhead').rules('add', {
                        required: false,
                        positive: true
                    });
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

            $('#cancel-button').click(function(ev){
                ev.preventDefault();
                window.location = "{{ route('contact_list') }}";
            });

            {{--
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

            $('#submit-button').click(function(ev){
                if (!$('#avatar').val() && $('#old_avatar').val()) {
                    ev.preventDefault();
                    uiAlert({type: 'error', text: 'The contact\'s picture is missing or not saved.'});
                }
                if (!$('#signature').val() && $('#old_signature').val()) {
                    ev.preventDefault();
                    uiAlert({type: 'error', text: 'The contact\'s signature is missing or not saved.'});
                }
            });
            --}}
        });
        {{--
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
        --}}
    </script>
@stop
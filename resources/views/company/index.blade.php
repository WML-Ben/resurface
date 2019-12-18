@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>Companies</span></li>
    </ul>
@stop

@section('content')
    <div class="page-content-inner">
        <div class="row">
            <div class="col-md-12">
                @include('errors._list')
                <div class="portlet box list-items admin-form">
                    <div class="portlet-body">
                        <div class="clearfix">
                            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15">
                                <div class="btn-group">
                                    @if (auth()->user()->hasPrivilege('create-company'))
                                        <a href="{{ route('company_create') }}" class="btn btn-success mr10">
                                            <i class="fa fa-plus mr10"></i>Create New
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15 search-container">
                                @if (auth()->user()->hasPrivilege('search-company'))
                                    {!! Form::jSearchForm($needle, route('company_search'), route('company_list')) !!}
                                @endif
                            </div>
                        </div>

                        <div class="">
                            <table class="table table-bordered list-table">
                                <thead>
                                    <tr>
                                        <th class="td-sortable">{!! SortableTrait::link('name', 'Name') !!}</th>
                                        <th class="tc">Location</th>
                                        <th class="td-sortable tc">{!! SortableTrait::link('phone', 'Phone') !!}</th>
                                        <th class="td-sortable tc">{!! SortableTrait::link('email', 'Email') !!}</th>
                                        <th class="td-sortable tc">{!! SortableTrait::link('companies.category_id|company_categories.name', 'Category') !!}</th>
                                        <th class="actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($companies as $company)
                                    <tr data-id="{{ $company->id }}" class="{{ !empty($company->disabled) ? 'disabled' : '' }}">
                                        <td>
                                            @if (auth()->user()->hasPrivilege('update-company'))
                                                <span title="Click to edit">
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $company->id }}"
                                                   data-name="name"
                                                   data-value="{{ $company->name }}"
                                                   data-js-validation-function="isPlainText"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule="required|plainText|unique:companies,name,{{ $company->id }}"
                                                   data-type="text"
                                                   data-title="Company Name:"
                                                   data-url="{{ route('company_inline_update') }}">
                                                    {{ $company->name }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $company->name }}
                                            @endif
                                        </td>
                                        <td class="tc">{!! $company->full_location !!}</td>
                                        <td class="tc">
                                            @if (auth()->user()->hasPrivilege('update-company'))
                                                <span title="Click to edit">
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $company->id }}"
                                                   data-name="phone"
                                                   data-value="{{ $company->phone }}"
                                                   data-js-validation-function="isPhone"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule="phone"
                                                   data-type="text"
                                                   data-title="Phone:"
                                                   data-url="{{ route('company_inline_update') }}">
                                                    {{ $company->phone }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $company->phone }}
                                            @endif
                                        </td>
                                        <td class="tc">
                                            @if (auth()->user()->hasPrivilege('update-company'))
                                                <span title="Click to edit">
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $company->id }}"
                                                   data-name="email"
                                                   data-value="{{ $company->email }}"
                                                   data-js-validation-function="isEmail"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule="email"
                                                   data-type="text"
                                                   data-title="Email:"
                                                   data-url="{{ route('company_inline_update') }}">
                                                    {{ $company->email }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $company->email }}
                                            @endif
                                        </td>
                                        <td class="tc">
                                            @if (auth()->user()->hasPrivilege('update-company'))
                                                <span data-toggle="tooltip" title="Click to edit">
                                                    <a class="x-editable" href="#"
                                                       data-pk="{{ $company->id }}"
                                                       data-name="category_id"
                                                       data-value="{{ $company->category_id }}"
                                                       data-source="{{ $json_company_categories_cb }}"
                                                       data-js-validation-function="isPositive"
                                                       data-js-validation-error-message="Invalid type."
                                                       data-php-validation-rule="required|positive"
                                                       data-type="select"
                                                       data-title="Category:"
                                                       data-url="{{ route('company_inline_update') }}">
                                                        {{ $company->category->name ?? '' }}
                                                    </a>
                                                </span>
                                            @else
                                                {{ $company->category->name ?? '' }}
                                            @endif
                                        </td>
                                        <td class="centered actions all-time-visible-actions">
                                            @if (auth()->user()->hasPrivilege('update-company'))
                                                <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('company_edit', ['id' => $company->id]) }}">Edit</a>
                                            @endif
                                            @if (auth()->user()->hasPrivilege('disable-company'))
                                                <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('company_toggle_status', ['id' => $company->id]) }}">{{  empty($company->disabled) ? 'Disable' : 'Enable' }}</a>
                                            @endif
                                            @if (auth()->user()->hasPrivilege('delete-company'))
                                                <a href="javascript:void(0);" class="action" data-action="delete" data-id="{{ $company->id }}">Delete</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {!! Form::jPaginator($companies, 'company_list') !!}

                        {!! Form::jDeleteForm(route('company_delete')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js-files')
    <script>
        $(function(){
            $('#password').val('');

            $('#profileForm').validate({
                rules: {
                    // required fields:
                    first_name: {
                        required  : true,
                        firstName: true
                    },
                    last_name: {
                        required: true,
                        lastName: true
                    },
                    title: {
                        required : true,
                        plainText: true
                    },
                    email: {
                        required: true,
                        email   : true
                    },
                    // no required fields:
                    password:{
                        required   : false,
                        password   : true,
                        rangelength: [6, 16]
                    },
                    repeat_password:{
                        required   : false,
                        password   : true,
                        minlength: 6,
                        equalTo  : '#password'
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
                    state: {
                        required : false,
                        plainText: true
                    },
                    zipcode: {
                        required : false,
                        plainText: true
                    },
                    country_id: {
                        required: false,
                        positive: true
                    },
                    phone: {
                        required: false,
                        phone   : true
                    },
                    facebook: {
                        required: false,
                        fullUrl : true
                    },
                    twitter: {
                        required: false,
                        fullUrl : true
                    },
                    instagram: {
                        required: false,
                        fullUrl : true
                    },
                    linkedin: {
                        required: false,
                        fullUrl : true
                    },
                    google_plus: {
                        required: false,
                        fullUrl : true
                    },
                    website: {
                        required: false,
                        fullUrl : true
                    },
                    bio: {
                        required: false,
                        text    : true
                    },
                    paypal_button_code: {
                        required: false,
                        text    : true
                    }
                },
                messages: {
                    password: {
                        rangelength: 'La contraseña debe tener entre {0} y {1} caracteres.',
                    },
                    repeat_password: 'Las contraseñas no coinciden.'
                }
            });

            $('#cancel-button').click(function(ev){
                ev.preventDefault();
                window.location = "{{ route('company_list') }}";
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
                        var result = $.parseJSON(response);
                        $('#state_id').empty();
                        if (result.success) {
                            var html = ['<option value="0">Selecciona Estado o Provincia</option>'];
                            $.each(result.data, function(index, value){
                                html.push('<option value="'+ index +'">'+ value +'</option>')
                            });
                            $('#state_id').html(html.join(''));
                        } else {
                            pnAlert({
                                type: 'error',
                                title: 'Error',
                                text: result.message,
                                addClass: 'mt50'
                            });
                        }
                    }
                });
            });

        });
    </script>
@stop
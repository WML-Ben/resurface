@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>AP Contacts</span></li>
    </ul>
@stop

@section('content')
    <!-- Image popup -->
    <div id="userAvatar" class="popup-basic popup-lg mfp-with-anim mfp-hide">
        <img class="img-responsive" src="{{ $siteUrl }}" alt="">
    </div>

    <div class="page-content-inner">
        <div class="row">
            <div class="col-md-12">
                @include('errors._list')
                <div class="portlet box list-items admin-form">
                    <div class="portlet-body">
                        <div class="clearfix">
                            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15">
                                <div class="btn-group">
                                    @if (auth()->user()->hasPrivilege('create-contact'))
                                        <a href="{{ route('contact_create') }}" class="btn btn-success mr10">
                                            <i class="fa fa-plus mr10"></i>Add New
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15 search-container">
                                @if (auth()->user()->hasPrivilege('search-contact'))
                                    {!! Form::jSearchForm($needle, route('contact_search'), route('contact_list')) !!}
                                @endif
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered list-table">
                                <thead>
                                <tr>
                                    <th class="td-sortable tc">{!! SortableTrait::link('first_name', 'First Name') !!}</th>
                                    <th class="td-sortable tc">{!! SortableTrait::link('last_name', 'Last Name') !!}</th>
                                    <th class="td-sortable tc xs-hidden">{!! SortableTrait::link('title') !!}</th>
                                    <th class="td-sortable tc xs-hidden">{!! SortableTrait::link('email') !!}</th>
                                    <th class="td-sortable tc xs-hidden">{!! SortableTrait::link('phone') !!}</th>
                                    <th class="td-sortable tc xs-hidden">{!! SortableTrait::link('users.company_id|companies.name', 'Company') !!}</th>
                                    <th class="td-sortable tc xs-hidden">{!! SortableTrait::link('users.company_position_id|company_positions.name', 'Position') !!}</th>
                                    <th class="actions">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contacts as $contact)
                                        <tr data-id="{{ $contact->id }}" class="not-tag {{ !empty($contact->disabled) ? 'disabled' : '' }}">
                                            @if (auth()->user()->hasPrivilege('update-contact'))
                                                <td class="tc">
                                                    <span data-toggle="tooltip" title="Click to edit">
                                                        <a class="x-editable" href="#"
                                                           data-pk="{{ $contact->id }}"
                                                           data-name="first_name"
                                                           data-value="{{ $contact->first_name }}"
                                                           data-js-validation-function="isPersonName"
                                                           data-js-validation-error-message="Invalid entry."
                                                           data-php-validation-rule="required|personName"
                                                           data-type="text"
                                                           data-title="First Name:"
                                                           data-url="{{ route('contact_inline_update') }}">
                                                            {{ $contact->first_name }}
                                                        </a>
                                                    </span>
                                                </td>
                                                <td class="tc">
                                                    <span data-toggle="tooltip" title="Click to edit">
                                                        <a class="x-editable" href="#"
                                                           data-pk="{{ $contact->id }}"
                                                           data-name="last_name"
                                                           data-value="{{ $contact->last_name }}"
                                                           data-js-validation-function="isPersonName"
                                                           data-js-validation-error-message="Invalid entry."
                                                           data-php-validation-rule="required|personName"
                                                           data-type="text"
                                                           data-title="Last Name:"
                                                           data-url="{{ route('contact_inline_update') }}">
                                                            {{ $contact->last_name }}
                                                        </a>
                                                    </span>
                                                </td>
                                                <td class="xs-hidden tc">
                                                    <span data-toggle="tooltip" title="Click to edit">
                                                        <a class="x-editable" href="#"
                                                           data-pk="{{ $contact->id }}"
                                                           data-name="title"
                                                           data-value="{{ $contact->title }}"
                                                           data-js-validation-function="isPlainText"
                                                           data-js-validation-error-message="Invalid title."
                                                           data-php-validation-rule="plainText"
                                                           data-type="text"
                                                           data-title="Title:"
                                                           data-url="{{ route('contact_inline_update') }}">
                                                            {{ $contact->title }}
                                                        </a>
                                                    </span>
                                                </td>
                                                <td class="xs-hidden tc">
                                                    <span data-toggle="tooltip" title="Click to edit">
                                                        <a class="x-editable" href="#"
                                                           data-pk="{{ $contact->id }}"
                                                           data-name="email"
                                                           data-value="{{ $contact->email }}"
                                                           data-js-validation-function="isEmail"
                                                           data-js-validation-error-message="Invalid email."
                                                           data-php-validation-rule="required|email|unique:contacts,email,{{ $contact->id }}"
                                                           data-type="text"
                                                           data-title="Email:"
                                                           data-url="{{ route('contact_inline_update') }}">
                                                            {{ $contact->email }}
                                                        </a>
                                                    </span>
                                                </td>
                                                <td class="xs-hidden tc">
                                                    <span data-toggle="tooltip" title="Click to edit">
                                                        <a class="x-editable" href="#"
                                                           data-pk="{{ $contact->id }}"
                                                           data-name="phone"
                                                           data-value="{{ $contact->phone }}"
                                                           data-js-validation-function="isPhone"
                                                           data-js-validation-error-message="Invalid phone."
                                                           data-php-validation-rule="phone"
                                                           data-type="text"
                                                           data-title="Phone:"
                                                           data-url="{{ route('contact_inline_update') }}">
                                                            {{ $contact->phone }}
                                                        </a>
                                                    </span>
                                                </td>
                                            @else
                                                <td class="tc">{{ $contact->first_name }}</td>
                                                <td class="tc">{{ $contact->last_name }}</td>
                                                <td class="xs-hidden tc">{{ $contact->title }}</td>
                                                <td class="xs-hidden tc">{{ $contact->email }}</td>
                                                <td class="xs-hidden tc">{{ $contact->phone }}</td>
                                            @endif
                                            <td class="xs-hidden tc">{{ $contact->company->name ?? '' }}</td>
                                            <td class="xs-hidden tc">{{ $contact->companyPosition->name ?? '' }}</td>
                                            <td class="centered actions all-time-visible-actions">
                                                @if (auth()->user()->hasPrivileges(['update-contact', 'delete-contact']))
                                                    @if (auth()->user()->hasPrivilege('update-contact'))
                                                        <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('contact_edit', ['id' => $contact->id]) }}">Edit</a>
                                                    @endif
                                                    @if (auth()->user()->hasPrivilege('disable-contact'))
                                                        <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('contact_toggle_status', ['id' => $contact->id]) }}">
                                                            {{ empty($contact->disabled) ? 'Disable' : 'Enable' }}</a>
                                                    @endif
                                                    @if (auth()->user()->hasPrivilege('delete-contact'))
                                                        <a href="javascript:;" class="action" data-action="delete" data-id="{{ $contact->id }}">Delete</a>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {!! Form::jPaginator($contacts, 'contact_list') !!}

                        {!! Form::jDeleteForm(route('contact_delete')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js-files')
    <script>
        $(function(){
            $('.show-image').click(function(){
                var src = $(this).find('img').attr('src');
                $('#userAvatar').find('img').attr('src', src).attr('alt', 'img');

                $(this).parents('tbody').find('.show-image').removeClass('active-animation');
                $(this).addClass('active-animation item-checked');

                $.magnificPopup.open({
                    removalDelay: 500, //delay removal by X to allow out-animation,
                    items: {
                        src: '#userAvatar'
                    },
                    callbacks: {
                        beforeOpen: function(e) {
                            this.st.mainClass = 'mfp-slideDown';
                        }
                    },
                    midClick: true
                });
            });
        });
    </script>
@stop

@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>AP Employees</span></li>
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
                                    @if (auth()->user()->hasPrivilege('create-employee'))
                                        <a href="{{ route('employee_create') }}" class="btn btn-success mr10">
                                            <i class="fa fa-plus mr10"></i>Add New
                                        </a>
                                    @endif
                                </div>
                                <span class="select-container">
                                    <label for="status_filter" class="select-label">Status</label>:
                                    <select id="status_filter">
                                        <option value="0"{!! empty($showAll) ? ' selected="selected"' : ''!!} data-url="{{ route('employee_list', array_merge(Request::query(), ['show_all' => 0])) }}">Active Employees</option>
                                        <option value="1"{!! !empty($showAll) ? ' selected="selected"' : ''!!} data-url="{{ route('employee_list', array_merge(Request::query(), ['show_all' => 1])) }}">All Employees</option>
                                    </select>
                                </span>
                            </div>
                            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15 search-container">
                                @if (auth()->user()->hasPrivilege('search-employee'))
                                    {!! Form::jSearchForm($needle, route('employee_search'), route('employee_list')) !!}
                                @endif
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered list-table">
                                <thead>
                                <tr>
                                    <th class="td-user-avatar tc xs-hidden">Avatar</th>
                                    <th class="td-sortable tc">{!! SortableTrait::link('first_name', 'First Name') !!}</th>
                                    <th class="td-sortable tc">{!! SortableTrait::link('last_name', 'Last Name') !!}</th>
                                    <th class="td-sortable tc xs-hidden">{!! SortableTrait::link('title') !!}</th>
                                    <th class="td-sortable tc xs-hidden">{!! SortableTrait::link('email') !!}</th>
                                    <th class="td-sortable tc xs-hidden">{!! SortableTrait::link('phone') !!}</th>
                                    <th class="td-sortable tc xs-hidden">{!! SortableTrait::link('employees.role_id|roles.name', 'Role') !!}</th>
                                    <th class="actions">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach ($employees as $employee)
                                        <tr data-id="{{ $employee->id }}" class="not-tag {{ !empty($employee->disabled) ? 'disabled' : '' }}">
                                            <td class="xs-hidden td-user-avatar prel tc">
                                                <a class="show-image" href="javascript:void(0)" data-toggle="tooltip" title="aumentar">
                                                    {!! !empty($employee->disabled) ?  '<div class="ribbon disabled"></div>' : '' !!}
                                                    @if (!empty($employee->avatar))
                                                        <img src="{{ $mediaUrl }}/avatars/{{  $employee->avatar }}" alt="{{ $employee->avatar }}" height="40"/>
                                                    @else
                                                        <img src="{{ $mediaUrl }}/avatars/{{ $config['defaultAvatar'] }}" alt="default avatar" height="40"/>
                                                    @endif
                                                </a>
                                            </td>
                                            @if (auth()->user()->role_id != $employee->role_id && auth()->user()->hasRole($employee->role->name) && auth()->user()->hasPrivilege('update-employee'))
                                                <td class="tc">
                                                    <span data-toggle="tooltip" title="Click to edit">
                                                        <a class="x-editable" href="#"
                                                           data-pk="{{ $employee->id }}"
                                                           data-name="first_name"
                                                           data-value="{{ $employee->first_name }}"
                                                           data-js-validation-function="isPersonName"
                                                           data-js-validation-error-message="Invalid entry."
                                                           data-php-validation-rule="required|personName"
                                                           data-type="text"
                                                           data-title="First Name:"
                                                           data-url="{{ route('employee_inline_update') }}">
                                                            {{ $employee->first_name }}
                                                        </a>
                                                    </span>
                                                </td>
                                                <td class="tc">
                                                    <span data-toggle="tooltip" title="Click to edit">
                                                        <a class="x-editable" href="#"
                                                           data-pk="{{ $employee->id }}"
                                                           data-name="last_name"
                                                           data-value="{{ $employee->last_name }}"
                                                           data-js-validation-function="isPersonName"
                                                           data-js-validation-error-message="Invalid entry."
                                                           data-php-validation-rule="required|personName"
                                                           data-type="text"
                                                           data-title="Last Name:"
                                                           data-url="{{ route('employee_inline_update') }}">
                                                            {{ $employee->last_name }}
                                                        </a>
                                                    </span>
                                                </td>
                                                <td class="xs-hidden tc">
                                                    <span data-toggle="tooltip" title="Click to edit">
                                                        <a class="x-editable" href="#"
                                                           data-pk="{{ $employee->id }}"
                                                           data-name="title"
                                                           data-value="{{ $employee->title }}"
                                                           data-js-validation-function="isPlainText"
                                                           data-js-validation-error-message="Invalid title."
                                                           data-php-validation-rule="plainText"
                                                           data-type="text"
                                                           data-title="Title:"
                                                           data-url="{{ route('employee_inline_update') }}">
                                                            {{ $employee->title }}
                                                        </a>
                                                    </span>
                                                </td>
                                                <td class="xs-hidden tc">
                                                    <span data-toggle="tooltip" title="Click to edit">
                                                        <a class="x-editable" href="#"
                                                           data-pk="{{ $employee->id }}"
                                                           data-name="email"
                                                           data-value="{{ $employee->email }}"
                                                           data-js-validation-function="isEmail"
                                                           data-js-validation-error-message="Invalid email."
                                                           data-php-validation-rule="required|email|unique:employees,email,{{ $employee->id }}"
                                                           data-type="text"
                                                           data-title="Email:"
                                                           data-url="{{ route('employee_inline_update') }}">
                                                            {{ $employee->email }}
                                                        </a>
                                                    </span>
                                                </td>
                                                <td class="xs-hidden tc">
                                                    <span data-toggle="tooltip" title="Click to edit">
                                                        <a class="x-editable" href="#"
                                                           data-pk="{{ $employee->id }}"
                                                           data-name="phone"
                                                           data-value="{{ $employee->phone }}"
                                                           data-js-validation-function="isPhone"
                                                           data-js-validation-error-message="Invalid phone."
                                                           data-php-validation-rule="required|phone"
                                                           data-type="text"
                                                           data-title="Phone:"
                                                           data-url="{{ route('employee_inline_update') }}">
                                                            {{ $employee->phone }}
                                                        </a>
                                                    </span>
                                                </td>
                                            @else
                                                <td class="tc">{{ $employee->first_name }}</td>
                                                <td class="tc">{{ $employee->last_name }}</td>
                                                <td class="xs-hidden tc">{{ $employee->title }}</td>
                                                <td class="xs-hidden tc">{{ $employee->email }}</td>
                                                <td class="xs-hidden tc">{{ $employee->phone }}</td>
                                            @endif
                                            <td class="xs-hidden tc">{{ $employee->role->name }}</td>
                                            <td class="centered actions all-time-visible-actions">
                                                @if (auth()->user()->role_id != $employee->role_id && auth()->user()->hasPrivileges(['update-employee', 'delete-employee']))
                                                    @if (auth()->user()->hasPrivilege('update-employee'))
                                                        <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('employee_edit', ['id' => $employee->id]) }}">Edit</a>
                                                    @endif
                                                    @if (auth()->user()->hasPrivilege('disable-employee'))
                                                        <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('employee_toggle_status', ['id' => $employee->id]) }}">{{ empty($employee->disabled) ? 'Disable' : 'Enable' }}</a>
                                                    @endif
                                                    @if (auth()->user()->hasPrivilege('delete-employee'))
                                                        <a href="javascript:;" class="action" data-action="delete" data-id="{{ $employee->id }}">Delete</a>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {!! Form::jPaginator($employees, 'employee_list') !!}

                        {!! Form::jDeleteForm(route('employee_delete')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js-files')
    <script>
        $(function(){
            $('.select-container select').multiselect();

            $('.select-container').on('change', 'select', function(){
                window.location = $(this).find('option:selected').data('url');
            });

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

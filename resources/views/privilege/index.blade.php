@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>AP Privileges</span></li>
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
                                    @if (auth()->user()->hasPrivilege('create-privilege'))
                                        <a href="{{ route('privilege_create') }}" class="btn btn-success mr10">
                                            <i class="fa fa-plus mr10"></i>Create New
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15 search-container">
                                @if (auth()->user()->hasPrivilege('search-privilege'))
                                    {!! Form::jSearchForm($needle, route('privilege_search'), route('privilege_list')) !!}
                                @endif
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered list-table">
                                <thead>
                                    <tr>
                                        <th class="td-sortable">{!! SortableTrait::link('privilege', 'Privilege') !!}</th>
                                        <th class="actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($privileges as $privilege)
                                    <tr data-id="{{ $privilege->id }}" class="{{ !empty($privilege->disabled) ? 'disabled' : '' }}">
                                        <td>
                                            @if (auth()->user()->hasPrivilege('modify-privilege'))
                                                <span data-toggle="tooltip" title="Click to edit">
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $privilege->id }}"
                                                   data-name="name"
                                                   data-value="{{ $privilege->name }}"
                                                   data-js-validation-function="isSlug"
                                                   data-js-validation-error-message="Only lowercase letters and dash character (-) are allowed."
                                                   data-php-validation-rule="required|slug|unique:privileges,name,{{ $privilege->id }}"
                                                   data-type="text"
                                                   data-title="Privilege Name:"
                                                   data-url="{{ route('privilege_inline_update') }}">
                                                    {{ $privilege->name }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $privilege->name }}
                                            @endif
                                        </td>
                                        <td class="centered actions">
                                            <ul class="nav navbar-nav">
                                                <li class="dropdown">
                                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                                    <ul class="dropdown-menu animated animated-short flipInX" role="menu">
                                                        @if (auth()->user()->hasPrivilege('update-privilege'))
                                                            <li>
                                                                <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('privilege_toggle_status', ['id' => $privilege->id]) }}">
                                                                    @if (empty($privilege->disabled))
                                                                        <span class="glyphicons glyphicons-ban"></span>Disable
                                                                    @else
                                                                        <span class="glyphicons glyphicons-ok"></span>Enable
                                                                    @endif
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if (auth()->user()->hasPrivilege('delete-privilege'))
                                                            <li class="menu-separator"></li>
                                                            <li>
                                                                <a href="javascript:void(0);" class="action" data-action="delete" data-id="{{ $privilege->id }}">
                                                                    <span class="glyphicons glyphicons-circle_remove"></span>Delete
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {!! Form::jPaginator($privileges, 'privilege_list') !!}

                        {!! Form::jDeleteForm(route('privilege_delete')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


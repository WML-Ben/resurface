@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>Resources:Vehicle Log Types</span></li>
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
                                    @if (auth()->user()->hasPrivilege('create-vehicle-log-type'))
                                        <a href="{{ route('vehicle_log_type_create') }}" class="btn btn-success mr10">
                                            <i class="fa fa-plus mr10"></i>Create New
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15 search-container">
                                @if (auth()->user()->hasPrivilege('search-vehicle-log-type'))
                                    {!! Form::jSearchForm($needle, route('vehicle_log_type_search'), route('vehicle_log_type_list')) !!}
                                @endif
                            </div>
                        </div>

                        <div class="">
                            <table class="table table-bordered list-table">
                                <thead>
                                    <tr>
                                        <th class="td-sortable">{!! SortableTrait::link('name', 'Name') !!}</th>
                                        <th class="actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($vehicleLogTypes as $vehicleLogType)
                                    <tr data-id="{{ $vehicleLogType->id }}" class="{{ !empty($vehicleLogType->disabled) ? 'disabled' : '' }}">
                                        <td>
                                            @if (true || auth()->user()->hasPrivilege('update-vehicle-log-type'))
                                                <span title="Click to edit">
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $vehicleLogType->id }}"
                                                   data-name="name"
                                                   data-value="{{ $vehicleLogType->name }}"
                                                   data-js-validation-function="isPlainText"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule="required|plainText|unique:vehicle_log_types,name,{{ $vehicleLogType->id }}"
                                                   data-type="text"
                                                   data-title="Name:"
                                                   data-url="{{ route('vehicle_log_type_inline_update') }}">
                                                    {{ $vehicleLogType->name }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $vehicleLogType->name }}
                                            @endif
                                        </td>

                                        <td class="centered actions">
                                            <ul class="nav navbar-nav">
                                                <li class="dropdown">
                                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                                    <ul class="dropdown-menu animated animated-short flipInX" role="menu">
                                                        @if (auth()->user()->hasPrivilege('update-vehicle-log-type'))
                                                            <li>
                                                                <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('vehicle_log_type_edit', ['id' => $vehicleLogType->id]) }}">
                                                                    <span class="fa fa-edit mr8"></span>Edit
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if (auth()->user()->hasPrivilege('delete-vehicle-log-type'))
                                                            <li class="menu-separator"></li>
                                                            <li>
                                                                <a href="javascript:void(0);" class="action" data-action="delete" data-id="{{ $vehicleLogType->id }}">
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

                        {!! Form::jPaginator($vehicleLogTypes, 'vehicle_log_type_list') !!}

                        {!! Form::jDeleteForm(route('vehicle_log_type_delete')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js-files')
    <script>

    </script>
@stop

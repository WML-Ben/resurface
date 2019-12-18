@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>Resources: Vehicle Types</span></li>
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
                                    @if (auth()->user()->hasPrivilege('create-vehicle-type'))
                                        <a href="{{ route('vehicle_type_create') }}" class="btn btn-success mr10">
                                            <i class="fa fa-plus mr10"></i>Create New
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15 search-container">
                                @if (auth()->user()->hasPrivilege('search-vehicle-type'))
                                    {!! Form::jSearchForm($needle, route('vehicle_type_search'), route('vehicle_type_list')) !!}
                                @endif
                            </div>
                        </div>

                        <div class="">
                            <table class="table table-bordered list-table">
                                <thead>
                                    <tr>
                                        <th class="td-sortable">{!! SortableTrait::link('name', 'Name') !!}</th>
                                        <th class="">Description</th>
                                        <th class="td-sortable tc">{!! SortableTrait::link('rate', 'Rate') !!}</th>
                                        <th class="actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($vehicleTypes as $vehicleType)
                                    <tr data-id="{{ $vehicleType->id }}" class="{{ !empty($vehicleType->disabled) ? 'disabled' : '' }}">
                                        <td>
                                            @if (auth()->user()->hasPrivilege('update-vehicle-type'))
                                                <span title="Click to edit">
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $vehicleType->id }}"
                                                   data-name="name"
                                                   data-value="{{ $vehicleType->name }}"
                                                   data-js-validation-function="isPlainText"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule="required|plainText|unique:vehicle_types,name,{{ $vehicleType->id }}"
                                                   data-type="text"
                                                   data-title="Name:"
                                                   data-url="{{ route('vehicle_type_inline_update') }}">
                                                    {{ $vehicleType->name }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $vehicleType->name }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (auth()->user()->hasPrivilege('update-vehicle-type'))
                                                <span title="Click to edit">
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $vehicleType->id }}"
                                                   data-name="description"
                                                   data-value="{{ $vehicleType->description }}"
                                                   data-js-validation-function="isPlainText"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule="required|plainText"
                                                   data-type="textarea"
                                                   data-title="Description:"
                                                   data-url="{{ route('vehicle_type_inline_update') }}">
                                                    {{ $vehicleType->description }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $vehicleType->description }}
                                            @endif
                                        </td>
                                        <td class="tc">
                                            @if (auth()->user()->hasPrivilege('update-vehicle-type'))
                                                <span title="Click to edit">$
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $vehicleType->id }}"
                                                   data-name="rate"
                                                   data-value="{{ $vehicleType->rate_float }}"
                                                   data-js-validation-function="isCurrency"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule="required|currency"
                                                   data-type="text"
                                                   data-title="Rate:"
                                                   data-url="{{ route('vehicle_type_inline_update') }}">
                                                    {{ $vehicleType->rate_float }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $vehicleType->rate_currency }}
                                            @endif
                                        </td>
                                        <td class="actions all-time-visible-actions">

                                            @if (auth()->user()->hasPrivilege('update-vehicle'))
                                                <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('vehicle_type_edit', ['id' => $vehicleType->id]) }}">Edit</a>
                                            @endif
                                            @if (auth()->user()->hasPrivilege('delete-vehicle'))
                                                <a href="javascript:;" class="action" data-action="delete" data-id="{{ $vehicleType->id }}">Delete</a>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {!! Form::jPaginator($vehicleTypes, 'vehicle_log_type_list') !!}

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

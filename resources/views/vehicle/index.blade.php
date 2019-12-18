@extends('layouts.layout')
@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>AP Vehicles</span></li>
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
                                    @if (auth()->user()->hasPrivilege('create-vehicle'))
                                        <a href="{{ route('vehicle_create') }}" class="btn btn-success mr10">
                                            <i class="fa fa-plus mr10"></i>Add New
                                        </a>
                                    @endif
                                </div>
                                <span class="select-container">
                                    <label for="status_filter" class="select-label">Status</label>:
                                    <select id="status_filter">
                                        <option value="0"{!! empty($showAll) ? ' selected="selected"' : ''!!} data-url="{{ route('vehicle_list', array_merge(Request::query(), ['show_all' => 0])) }}">Active Vehicles</option>
                                        <option value="1"{!! !empty($showAll) ? ' selected="selected"' : ''!!} data-url="{{ route('vehicle_list', array_merge(Request::query(), ['show_all' => 1])) }}">All Vehicles</option>
                                    </select>
                                </span>
                            </div>
                            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15 search-container">
                                @if (auth()->user()->hasPrivilege('search-vehicle'))
                                    {!! Form::jSearchForm($needle, route('vehicle_search'), route('vehicle_list')) !!}
                                @endif
                            </div>
                        </div>
                        <div>
                            <table class="table table-bordered list-table">
                                <thead>
                                    <tr>
                                        <th class="td-sortable tc">{!! SortableTrait::link('name', 'Name') !!}</th>
                                        <th class="td-sortable tc xs-hidden">{!! SortableTrait::link('vehicles.type_id|vehicle_types.name', 'Type') !!}</th>
                                        <th class="td-sortable tc xs-hidden">{!! SortableTrait::link('vin_number', 'Vin Number') !!}</th>
                                        <th class="td-sortable tc xs-hidden">{!! SortableTrait::link('vehicles.location_id|locations.name', 'Location') !!}</th>
                                        <th class="td-sortable tc xs-hidden">{!! SortableTrait::link('purchased_at','Purchase Date') !!}</th>
                                        <th class="actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vehicles as $vehicle)
                                        <tr data-id="{{ $vehicle->id }}" class="not-tag {{ !empty($vehicle->disabled) ? 'disabled' : '' }}">
                                            @if (auth()->user()->hasPrivilege('update-vehicle'))
                                                <td class="tc">
                                                    <span data-toggle="tooltip" title="Click to edit">
                                                        <a class="x-editable" href="#"
                                                           data-pk="{{ $vehicle->id }}"
                                                           data-name="name"
                                                           data-value="{{ $vehicle->name }}"
                                                           data-js-validation-function="isPlainText"
                                                           data-js-validation-error-message="Invalid entry."
                                                           data-php-validation-rule="required|plainText|unique:vehicles,name,{{ $vehicle->id }}"
                                                           data-type="text"
                                                           data-title="Name:"
                                                           data-url="{{ route('vehicle_inline_update') }}">
                                                            {{ $vehicle->name }}
                                                        </a>
                                                    </span>
                                                </td>
                                                <td class="tc">
                                                    <span data-toggle="tooltip" title="Click to edit">
                                                        <a class="x-editable" href="#"
                                                           data-pk="{{ $vehicle->id }}"
                                                           data-name="type_id"
                                                           data-value="{{ $vehicle->type_id }}"
                                                           data-source="{{ $json_vehicle_types_cb }}"
                                                           data-js-validation-function="isPositive"
                                                           data-js-validation-error-message="Invalid type."
                                                           data-php-validation-rule="required|positive"
                                                           data-type="select"
                                                           data-title="Type:"
                                                           data-url="{{ route('vehicle_inline_update') }}">
                                                            {{ $vehicle->vehicleType->name }}
                                                        </a>
                                                    </span>
                                                </td>
                                                <td class="xs-hidden tc">
                                                    <span data-toggle="tooltip" title="Click to edit">
                                                        <a class="x-editable" href="#"
                                                           data-pk="{{ $vehicle->id }}"
                                                           data-name="vin_number"
                                                           data-value="{{ $vehicle->vin_number }}"
                                                           data-js-validation-function="isPlainText"
                                                           data-js-validation-error-message="Invalid Vin Number."
                                                           data-php-validation-rule="plainText"
                                                           data-type="text"
                                                           data-title="Vin Number:"
                                                           data-url="{{ route('vehicle_inline_update') }}">
                                                            {{ $vehicle->vin_number }}
                                                        </a>
                                                    </span>
                                                </td>
                                                <td class="xs-hidden tc">
                                                    <span data-toggle="tooltip" title="Click to edit">
                                                        <a class="x-editable" href="#"
                                                           data-pk="{{ $vehicle->id }}"
                                                           data-name="location_id"
                                                           data-value="{{ $vehicle->location_id }}"
                                                           data-source="{{ $json_locations_cb }}"
                                                           data-js-validation-function="isPositive"
                                                           data-js-validation-error-message="Invalid type."
                                                           data-php-validation-rule="required|positive"
                                                           data-type="select"
                                                           data-title="Location:"
                                                           data-url="{{ route('vehicle_inline_update') }}">
                                                            {{ $vehicle->location->name }}
                                                        </a>
                                                    </span>
                                                </td>
                                            @else
                                                <td class="xs-hidden tc">{{ $vehicle->name }}</td>
                                                <td class="xs-hidden tc">{{ $vehicle->vehicleType->name }}</td>
                                                <td class="xs-hidden tc">{{ $vehicle->vin_number }}</td>
                                                <td class="xs-hidden tc">{{ $vehicle->location->name }}</td>
                                            @endif
                                            <td class="xs-hidden tc">{{ $vehicle->html_purchased_at }}</td>
                                            <td class="actions all-time-visible-actions">
                                                @if (auth()->user()->hasPrivilege('update-vehicle'))
                                                    <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('vehicle_edit', ['id' => $vehicle->id]) }}">Edit</a>
                                                @endif
                                                @if (auth()->user()->hasPrivilege('disable-vehicle'))
                                                    <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('vehicle_toggle_status', ['id' => $vehicle->id]) }}">{{ empty($vehicle->disabled) ? 'Disable' : 'Enable' }}</a>
                                                @endif
                                                @if (auth()->user()->hasPrivilege('delete-vehicle'))
                                                    <a href="javascript:;" class="action" data-action="delete" data-id="{{ $vehicle->id }}">Delete</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {!! Form::jPaginator($vehicles, 'vehicle_list') !!}

                        {!! Form::jDeleteForm(route('vehicle_delete')) !!}
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
        });
    </script>
@stop

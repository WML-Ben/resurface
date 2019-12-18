@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>{{ empty($statusCB) ? 'Active ' : '' }}Work Orders</span></li>
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
                                {{--
                                @if (!empty($statusCB))
                                    <span class="select-container">
                                        <label for="status_filter" class="select-label">Status</label>:
                                        <select id="status_filter">
                                            @foreach ($statusCB as $key => $value)
                                                <option value="{{ $key }}"{!! !empty($statusId) && $statusId == $key ? ' selected="selected"' : ''!!} data-url="{{ route('work_order_list', array_merge(Request::query(), ['statusId' => $key])) }}">{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </span>
                                @endif
                                --}}
                            </div>
                            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15 search-container">
                                @if (auth()->user()->hasPrivilege('search-work-order'))
                                    {!! Form::jSearchForm($needle, route('work_order_search'), route('work_order_list')) !!}
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered list-table scrollable">
                                <thead>
                                    <tr>
                                        <th class="td-sortable td-name tc">{!! SortableTrait::link('order_number', 'Number') !!}</th>
                                        <th class="td-sortable td-name">{!! SortableTrait::link('name', 'Name') !!}</th>
                                        <th class="td-sortable td-name">{!! SortableTrait::link('orders.property_id|properties.name', 'Property') !!}</th>
                                        <th class="td-name">Location</th>
                                        <th class="td-sortable td-name">{!! SortableTrait::link('orders.company_id|companies.name', 'Company') !!}</th>
                                        {{--
                                        @if (!empty($statusCB))
                                            <th class="td-sortable tc td-date">{!! SortableTrait::link('orders.status_id|order_status.name', 'Order Status') !!}</th>
                                        @endif
                                        {{--
                                        <th class="td-sortable tc td-date">{!! SortableTrait::link('orders.sales_manager_id|users.first_name', 'Sales Manager') !!}</th>
                                        <th class="td-sortable tc td-date">{!! SortableTrait::link('orders.sales_person_id|users.first_name', 'Sales Person') !!}</th>
                                        --}}
                                        <th class="actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="tbfs12">
                                @foreach ($workOrders as $workOrder)
                                    <tr data-id="{{ $workOrder->id }}" class="not-tag {{ !empty($workOrder->alert) ? ' alert' : (!empty($workOrder->disabled) ? ' disabled' : (!empty($workOrder->status_id) ? ' status-'. $workOrder->status_id : '')) }}">
                                        <td class=" prel fs20 tc">
                                            <div class="ribbon"></div>
                                            {!! $workOrder->order_number !!}
                                        </td>
                                        <td class="td-name">{{ $workOrder->name }}</td>
                                        <td class="td-name">{{ $workOrder->property->name ?? 'unknown' }}</td>
                                        <td class="td-name">{!! $workOrder->full_location ?? 'unknown' !!}</td>
                                        <td class="td-name">{{ $workOrder->company->name ?? 'unknown' }}</td>
                                        @if (!empty($statusCB))
                                            <td class="tc td-date">{{ $workOrder->status->name ?? 'unknown' }}</td>
                                        @endif
                                        {{--
                                        <td class="tc">
                                            @if (auth()->user()->hasPrivilege('update-proposal'))
                                                <span title="Click to edit">
                                                    <a class="x-editable" href="#"
                                                       data-pk="{{ $workOrder->id }}"
                                                       data-name="sales_manager_id"
                                                       data-value="{{ $workOrder->sales_manager_id }}"
                                                       data-source="{{ $json_sales_managers_cb }}"
                                                       data-js-validation-function="isPositive"
                                                       data-js-validation-error-message="Invalid type."
                                                       data-php-validation-rule="required|positive"
                                                       data-type="select"
                                                       data-title="Sales Manager:"
                                                       data-url="{{ route('work_order_inline_update') }}">
                                                        {{ $workOrder->salesManager->fullName ?? 'unknown' }}
                                                    </a>
                                                </span>
                                            @else
                                                {{ $workOrder->salesManager->fullName ?? 'unknown' }}
                                            @endif
                                        </td>
                                        <td class="tc">
                                            @if (auth()->user()->hasPrivilege('update-proposal'))
                                                <span title="Click to edit">
                                                    <a class="x-editable" href="#"
                                                       data-pk="{{ $workOrder->id }}"
                                                       data-name="sales_person_id"
                                                       data-value="{{ $workOrder->sales_person_id }}"
                                                       data-source="{{ $json_sales_persons_cb }}"
                                                       data-js-validation-function="isPositive"
                                                       data-js-validation-error-message="Invalid type."
                                                       data-php-validation-rule="required|positive"
                                                       data-type="select"
                                                       data-title="Sales Person:"
                                                       data-url="{{ route('work_order_inline_update') }}">
                                                        {{ $workOrder->salesPerson->fullName ?? 'unknown' }}
                                                    </a>
                                                </span>
                                            @else
                                                {{ $workOrder->salesPerson->fullName ?? 'unknown' }}
                                            @endif
                                        </td>
                                        --}}
                                        <td class="centered actions">
                                            <ul class="nav navbar-nav">
                                                <li class="dropdown">
                                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                                    <ul class="dropdown-menu animated animated-short flipInX" role="menu">
                                                        @if (auth()->user()->hasPrivilege('update-work-order'))
                                                            <li>
                                                                <a href="javascript:void(0);" class="not-yet-available action" data-action="route" data-route="{{ route('work_order_services', ['id' => $workOrder->id]) }}">
                                                                    <span class="fa fa-cogs mr8"></span>Manage Services
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if (auth()->user()->hasPrivilege('add-note-work-order'))
                                                            <li>
                                                                <a href="javascript:void(0);" class="not-yet-available action" data-action="route" data-route="{{ route('work_order_note_create', ['id' => $workOrder->id]) }}">
                                                                    <span class="fa fa-comment-o mr8"></span>Add Notes
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if (auth()->user()->hasPrivilege('add-upload-work-order'))
                                                            <li>
                                                                <a href="javascript:void(0);" class="not-yet-available action" data-action="route" data-route="{{ route('work_order_upload_form', ['id' => $workOrder->id]) }}">
                                                                    <span class="fa fa-upload mr8"></span>Add Upload
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if (auth()->user()->hasPrivilege('update-work-order'))
                                                            <li>
                                                                <a href="javascript:void(0);" class="not-yet-available action" data-action="route" data-route="{{ route('work_order_notices', ['id' => $workOrder->id]) }}">
                                                                    <span class="fa fa-exclamation mr8"></span>Client Notices
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if (auth()->user()->hasPrivilege('print-work-order'))
                                                            <li>
                                                                <a href="javascript:void(0);" class="not-yet-available action" data-action="route" data-route="{{ route('work_order_print', ['id' => $workOrder->id]) }}">
                                                                    <span class="fa fa-print mr8"></span>Print Work Order
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if (auth()->user()->hasPrivilege('print-proposal'))
                                                            <li>
                                                                <a href="javascript:void(0);" class="not-yet-available action" data-action="route" data-route="{{ route('proposal_print', ['id' => $workOrder->id]) }}">
                                                                    <span class="fa fa-print mr8"></span>Print Proposal
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if (auth()->user()->hasPrivileges(['disable-work-order', 'delete-work-order']))
                                                            <li class="menu-separator"></li>
                                                            @if (auth()->user()->hasPrivilege('disable-work-order'))
                                                                <li>
                                                                    <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('work_order_toggle_status', ['id' => $workOrder->id]) }}">
                                                                        @if (empty($workOrder->disabled))
                                                                            <span class="glyphicons glyphicons-ban"></span>Disable
                                                                        @else
                                                                            <span class="glyphicons glyphicons-ok"></span>Enable
                                                                        @endif
                                                                    </a>
                                                                </li>
                                                            @endif
                                                            @if (auth()->user()->hasPrivilege('delete-work-order'))
                                                                <li>
                                                                    <a href="javascript:void(0);" class="action" data-action="delete" data-id="{{ $workOrder->id }}">
                                                                        <span class="glyphicons glyphicons-circle_remove"></span>Delete
                                                                    </a>
                                                                </li>
                                                            @endif
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

                        {!! Form::jPaginator($workOrders, 'work_order_list') !!}

                        {!! Form::jDeleteForm(route('work_order_delete')) !!}
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

        function xeditCallback(el, response, newValue)
        {
            if (response.field == 'status_id') {
                el.parents('tr').removeClass('status-' + response.old_value).addClass('status-' + newValue);
            }
        }
    </script>
@stop


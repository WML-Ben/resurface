@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>Order Services</span></li>
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
                            <div class="col-sm-12 col-md-7 pl0 ml0 pr0 mr0 mb15">
                                <span class="select-container order-services">
                                    <label for="status_filter" class="select-label">Status</label>:
                                    <select id="status_filter">
                                        @foreach ($statusCB as $key => $value)
                                            <option value="{{ $key }}"{!! !empty($statusId) && $statusId == $key ? ' selected="selected"' : ''!!} data-url="{{ route('order_service_list', array_merge(Request::query(), ['statusId' => $key])) }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </span>
                            </div>
                            <div class="col-sm-12 col-md-5 pl0 ml0 pr0 mr0 mb15 search-container">
                                @if (auth()->user()->hasPrivilege('search-order-service'))
                                    {!! Form::jSearchForm($needle, route('order_service_search'), route('order_service_list')) !!}
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bserviceed list-table scrollable">
                                <thead>
                                    <tr>
                                        <th class="td-sortable td-name">{!! SortableTrait::link('order_services.order_id|orders.order_number', 'Work Order') !!}</th>
                                        <th class="td-sortable td-name">{!! SortableTrait::link('name', 'Service') !!}</th>
                                        <th class="td-sortable tc td-date">{!! SortableTrait::link('order_services.order_service_status_id|order_service_status.name', 'Status') !!}</th>
                                        <th class="td-sortable tc td-date">{!! SortableTrait::link('started_at', 'Schedule') !!}</th>
                                        <th class="td-name">Location</th>
                                        <th class="td-sortable td-name">{!! SortableTrait::link('order_services.order_id|orders-id-property_id|properties.name', 'Property') !!}</th>
                                        {{--
                                        <th class="td-sortable td-name">{!! SortableTrait::link('order_services.order_id|orders-id-company_id|companies.name', 'Company') !!}</th>
                                        --}}
                                        <th class="td-sortable tc td-date">{!! SortableTrait::link('order_services.manager_id|users.first_name', 'Manager') !!}</th>
                                        <th class="td-sortable tc td-date">{!! SortableTrait::link('order_services.sub_manager_id|users.first_name', 'Sub Manager') !!}</th>
                                        <th class="actions only-edit-link">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="tbfs12">
                                @foreach ($orderServices as $orderService)
                                    <tr data-id="{{ $orderService->id }}" class="not-tag {{ !empty($orderService->alert) ? ' alert' : (!empty($orderService->disabled) ? ' disabled' : (!empty($orderService->status_id) ? ' status-'. $orderService->status_id : '')) }}">
                                        <td class="td-name prel">
                                            <div class="ribbon"></div>
                                            {{ $orderService->order->order_number ?? '' }}
                                        </td>
                                        <td class="td-name">{{ $orderService->name ?? 'unknown' }}</td>
                                        <td class="tc td-date">{{ $orderService->orderServiceStatus->name ?? 'unknown' }}</td>
                                        <td class="td-name">{!! $orderService->schedule !!}</td>
                                        <td class="td-name">{!! $orderService->full_location ?? 'unknown' !!}</td>
                                        <td class="td-name">{{ $orderService->order->property->name ?? 'unknown' }}</td>
                                        {{--
                                        <td class="td-name">{{ $orderService->order->company->name ?? 'unknown' }}</td>
                                        --}}
                                        <td class="tc">
                                            @if (auth()->user()->hasPrivilege('update-order-service'))
                                                <span title="Click to edit">
                                                    <a class="x-editable" href="#"
                                                       data-pk="{{ $orderService->id }}"
                                                       data-name="manager_id"
                                                       data-value="{{ $orderService->manager_id }}"
                                                       data-source="{{ $json_job_site_managers_cb }}"
                                                       data-js-validation-function="isPositive"
                                                       data-js-validation-error-message="Invalid type."
                                                       data-php-validation-rule="required|positive"
                                                       data-type="select"
                                                       data-title="Manager:"
                                                       data-url="{{ route('order_service_inline_update') }}">
                                                       {{ $orderService->manager->fullName ?? 'unknown' }}
                                                    </a>
                                                </span>
                                            @else
                                                 {{ $orderService->manager->fullName ?? 'unknown' }}
                                            @endif
                                        </td>
                                        <td class="tc">
                                            @if (auth()->user()->hasPrivilege('update-order-service'))
                                                <span title="Click to edit">
                                                    <a class="x-editable" href="#"
                                                       data-pk="{{ $orderService->id }}"
                                                       data-name="sub_manager_id"
                                                       data-value="{{ $orderService->sub_manager_id }}"
                                                       data-source="{{ $json_job_site_managers_cb }}"
                                                       data-js-validation-function="isPositive"
                                                       data-js-validation-error-message="Invalid type."
                                                       data-php-validation-rule="required|positive"
                                                       data-type="select"
                                                       data-title="Sub Manager:"
                                                       data-url="{{ route('order_service_inline_update') }}">
                                                        {{ $orderService->subManager->fullName ?? 'unknown' }}
                                                    </a>
                                                </span>
                                            @else
                                                {{ $orderService->subManager->fullName ?? 'unknown' }}
                                            @endif
                                        </td>
                                        <td class="centered actions all-time-visible-actions">
                                            @if (auth()->user()->hasPrivilege('update-work-order'))
                                                <a href="javascript:void(0);" class="action not-yet-available" data-action="route" data-route="{{ route('order_service_edit', ['id' => $orderService->id]) }}">Edit</a>
                                            @endif
                                            {{--
                                            @if (auth()->user()->hasPrivilege('print-order-service'))
                                                <a href="javascript:void(0);" class="not-yet-available action" data-action="route" data-route="{{ route('order_service_print', ['id' => $orderService->id]) }}">Print Order Service</a>
                                            @endif
                                            @if (empty($orderService->order->hold_as_permit_is_required))
                                                @if (auth()->user()->hasPrivilege('schedule-order-service'))
                                                    <a href="javascript:void(0);" class="not-yet-available action" data-action="route" data-route="{{ route('order_service_services', ['id' => $orderService->id]) }}">Schedule Service</a>
                                                @endif
                                                @if (auth()->user()->hasPrivilege('pre-day-checklist-order-service'))
                                                    <a href="javascript:void(0);" class="not-yet-available action" data-action="route" data-route="{{ route('order_service_upload_form', ['id' => $orderService->id]) }}">Pre Day Checklist</a>
                                                @endif
                                                @if (auth()->user()->hasPrivilege('end-of-day-checklist-order-service'))
                                                        <a href="javascript:void(0);" class="not-yet-available action" data-action="route" data-route="{{ route('order_service_upload_form', ['id' => $orderService->id]) }}">End of Day Checklist</a>
                                                @endif
                                                @if (auth()->user()->hasPrivilege('add-upload-order-service'))
                                                    <a href="javascript:void(0);" class="not-yet-available action" data-action="route" data-route="{{ route('order_service_upload_form', ['id' => $orderService->id]) }}">Add Upload</a>
                                                @endif
                                                @if (auth()->user()->hasPrivilege('mark-completed-order-service'))
                                                    <a href="javascript:void(0);" class="not-yet-available action" data-action="route" data-route="{{ route('order_service_notices', ['id' => $orderService->id]) }}">Mark Completed</a>
                                                @endif
                                            @endif
                                            @if (auth()->user()->hasPrivilege('put-on-hold-order-service'))
                                                <a href="javascript:void(0);" class="not-yet-available action" data-action="route" data-route="{{ route('order_service_notices', ['id' => $orderService->id]) }}">Put On Hold</a>
                                            @endif
                                            @if (auth()->user()->hasPrivileges(['disable-order-service', 'delete-order-service']))
                                                <li class="menu-separator"></li>
                                                @if (auth()->user()->hasPrivilege('disable-order-service'))
                                                    <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('order_service_toggle_status', ['id' => $orderService->id]) }}">{{ empty($orderService->disabled) ? 'Disable' : 'Enable' }}</a>
                                                @endif
                                                @if (auth()->user()->hasPrivilege('delete-order-service'))
                                                    <a href="javascript:void(0);" class="action" data-action="delete" data-id="{{ $orderService->id }}">Delete
                                                @endif
                                            @endif
                                            --}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {!! Form::jPaginator($orderServices, 'order_service_list') !!}

                        {!! Form::jDeleteForm(route('order_service_delete')) !!}
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


@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>Work Orders: <strong>Processing</strong></span></li>
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
                            <div class="col-sm-6 pl0 ml0 pr0 mr0 mb15 xs-hidden">
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
                                        <th class="actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="tbfs12">
                                @foreach ($workOrders as $workOrder)
                                    <tr data-id="{{ $workOrder->id }}" class="not-tag {{ !empty($workOrder->alert) ? ' alert' : (!empty($workOrder->disabled) ? ' disabled' : '') }}">
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
                                        <td class="centered actions all-time-visible-actions">
                                            @if (auth()->user()->hasPrivilege('update-work-order'))
                                                <a href="javascript:void(0);" class="action not-yet-available" data-action="route" data-route="{{ route('work_order_edit', ['id' => $workOrder->id]) }}">Edit</a>
                                            @endif
                                            {{--
                                            @if (auth()->user()->hasPrivilege('update-work-order'))
                                                <a href="javascript:void(0);" class="not-yet-available action" data-action="route" data-route="{{ route('work_order_services', ['id' => $workOrder->id]) }}">Manage Services</a>
                                            @endif
                                            @if (auth()->user()->hasPrivilege('add-note-work-order'))
                                                <a href="javascript:void(0);" class="not-yet-available action" data-action="route" data-route="{{ route('work_order_note_create', ['id' => $workOrder->id]) }}">Add Notes</a>
                                            @endif
                                            @if (auth()->user()->hasPrivilege('add-upload-work-order'))
                                                <a href="javascript:void(0);" class="not-yet-available action" data-action="route" data-route="{{ route('work_order_upload_form', ['id' => $workOrder->id]) }}">Add Upload</a>
                                            @endif
                                            @if (auth()->user()->hasPrivilege('update-work-order'))
                                                <a href="javascript:void(0);" class="not-yet-available action" data-action="route" data-route="{{ route('work_order_notices', ['id' => $workOrder->id]) }}">Client Notices</a>
                                            @endif
                                            @if (auth()->user()->hasPrivilege('print-work-order'))
                                                <a href="javascript:void(0);" class="not-yet-available action" data-action="route" data-route="{{ route('work_order_print', ['id' => $workOrder->id]) }}">Print Work Order</a>
                                            @endif
                                            @if (auth()->user()->hasPrivilege('print-proposal'))
                                                <a href="javascript:void(0);" class="not-yet-available action" data-action="route" data-route="{{ route('proposal_print', ['id' => $workOrder->id]) }}">Print Proposal</a>
                                            @endif
                                            @if (auth()->user()->hasPrivileges(['disable-work-order', 'delete-work-order']))
                                                @if (auth()->user()->hasPrivilege('disable-work-order'))
                                                    <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('work_order_toggle_status', ['id' => $workOrder->id]) }}">{{ empty($workOrder->disabled) ? 'Disable' : 'Enable' }}</a>
                                                @endif
                                                @if (auth()->user()->hasPrivilege('delete-work-order'))
                                                    <a href="javascript:void(0);" class="action" data-action="delete" data-id="{{ $workOrder->id }}">Delete</a>
                                                @endif
                                            @endif
                                            --}}
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


@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>Appointments</span></li>
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
                                <div class="btn-group hidden">
                                    @if (auth()->user()->hasPrivilege('create-appointment'))
                                        <a href="{{ route('appointment_create') }}" class="btn btn-success mr10">
                                            <i class="fa fa-plus mr10"></i>Create New
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15 search-container">
                                @if (auth()->user()->hasPrivilege('search-appointment'))
                                    {!! Form::jSearchForm($needle, route('appointment_search'), route('appointment_list')) !!}
                                @endif
                            </div>
                        </div>

                        <div class="">
                            <table class="table table-bordered list-table">
                                <thead>
                                    <tr>
                                        <th class="td-sortable tc td-date">{!! SortableTrait::link('started_at', 'At') !!}</th>
                                        <th class="td-sortable tc">{!! SortableTrait::link('name', 'Name') !!}</th>
                                        <th class="td-sortable tc">{!! SortableTrait::link('calendar_events.user_id|users.first_name', 'Sales Manager') !!}</th>
                                        <th class="td-sortable tc">{!! SortableTrait::link('calendar_events.property_id|properties-id-manager_id|users.first_name', 'Contact Manager') !!}</th>
                                        <th class="td-sortable tc hidden">{!! SortableTrait::link('calendar_events.property_id|properties.name', 'Property') !!}</th>
                                        <th class="td-sortable tc hidden">{!! SortableTrait::link('calendar_events.created_by|users.first_name', 'Created By') !!}</th>
                                        <th class="actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="tbfs12">
                                    @foreach ($appointments as $appointment)
                                        <tr data-id="{{ $appointment->id }}" class="{{ !empty($appointment->disabled) ? 'disabled' : '' }}">
                                            <td class="tc appointment-started-at">{!! $appointment->html_started_at !!}</td>
                                            <td class="tc appointment-name">{{ $appointment->name }}</td>
                                            <td class="tc appointment-user">{!! $appointment->user->html_full_name_and_contact_info ?? '' !!}</td>
                                            <td class="tc appointment-manager">{!! $appointment->property->manager->html_full_name_and_contact_info ?? '' !!}</td>
                                            <td class="tc appointment-property hidden">{!! $appointment->property->html_name_and_short_location ?? '' !!}</td>
                                            <td class="tc appointment-creator hidden">{!! $appointment->createdBy->fullName ?? '' !!}</td>
                                            <td class="centered actions all-time-visible-actions">
                                                @if (auth()->user()->hasPrivilege('show-appointment'))
                                                    <a href="javascript:void(0);" class="action show-details">View Details</a>
                                                @endif
                                                @if (auth()->user()->hasPrivilege('update-appointment'))
                                                    <a href="javascript:void(0);" class="action not-yet-available" data-action="route" data-route="{{ route('appointment_edit', ['id' => $appointment->id]) }}">Edit</a>
                                                @endif
                                                {{--
                                                @if (auth()->user()->hasPrivilege('disable-appointment'))
                                                    <a href="javascript:void(0);" class="action not-yet-available" data-action="route" data-route="{{ route('appointment_toggle_status', ['id' => $appointment->id]) }}">{{  empty($appointment->disabled) ? 'Disable' : 'Enable' }}</a>
                                                @endif
                                                @if (auth()->user()->hasPrivilege('delete-appointment'))
                                                    <a href="javascript:void(0);" class="action" data-action="delete" data-id="{{ $appointment->id }}">Delete</a>
                                                @endif
                                                --}}
                                                <div class="hidden">
                                                    <span class="appointment-ended-at">{!! $appointment->html_ended_at !!}</span>
                                                    <span class="appointment-description">{{ $appointment->description }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {!! Form::jPaginator($appointments, 'appointment_list') !!}

                        {!! Form::jDeleteForm(route('appointment_delete')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal_show_details" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content jform-container">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                    <h4 class="modal-title">Appointment Details</h4>
                </div>
                <div class="modal-body jform-body pt20">
                    <div class="row">
                        <div class="col-sm-6 admin-form-item-widget">
                            <div class="show-wrapper">
                                {{ Form::jShow('&nbsp;', ['label' => 'From', 'id' => 'modal_started_at', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-calendar']) }}
                            </div>
                        </div>
                        <div class="col-sm-6 admin-form-item-widget">
                            <div class="show-wrapper">
                                {{ Form::jShow('&nbsp;', ['label' => 'To', 'id' => 'modal_ended_at', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-calendar']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 admin-form-item-widget">
                            <div class="show-wrapper">
                                {{ Form::jShow('&nbsp;', ['label' => 'Title', 'id' => 'modal_name', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-bookmark']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 admin-form-item-widget">
                            <div class="show-wrapper">
                                {{ Form::jShow('&nbsp;', ['label' => 'Description', 'id' => 'modal_description', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-bookmark']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 admin-form-item-widget">
                            <div class="show-wrapper">
                                {{ Form::jShow('&nbsp;', ['label' => 'AP Employee', 'id' => 'modal_employee', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-user']) }}
                            </div>
                        </div>
                        <div class="col-sm-6 admin-form-item-widget">
                            <div class="show-wrapper">
                                {{ Form::jShow('&nbsp;', ['label' => 'Contact Manager', 'id' => 'modal_manager', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-user']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 admin-form-item-widget">
                            <div class="show-wrapper">
                                {{ Form::jShow('&nbsp;', ['label' => 'Property', 'id' => 'modal_property', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-building']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 admin-form-item-widget">
                            <div class="show-wrapper">
                                {{ Form::jShow('&nbsp;', ['label' => 'Created By', 'id' => 'modal_creator', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-user']) }}
                            </div>
                        </div>
                        <div class="col-sm-6 xs-hidden admin-form-item-widget"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js-files')
    <script>
        $(function(){
            $('.show-details').click(function(ev){
                var $row = $(this).parents('tr');

                $('#modal_started_at').html($row.find('.appointment-started-at').html().replace('<br>', ' @ '));
                $('#modal_ended_at').html($row.find('.appointment-ended-at').html().replace('<br>', ' @ '));
                $('#modal_name').html($row.find('.appointment-name').html());
                $('#modal_description').html($row.find('.appointment-description').html());
                $('#modal_employee').html($row.find('.appointment-user').html());
                $('#modal_manager').html($row.find('.appointment-manager').html());
                $('#modal_property').html($row.find('.appointment-property').html());
                $('#modal_creator').html($row.find('.appointment-creator').html());

                $('#modal_show_details').modal('show');
            });
        });
    </script>
@stop
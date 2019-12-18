@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>Tasks</span></li>
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
                                    @if (auth()->user()->hasPrivilege('create-task'))
                                        <a href="{{ route('task_create') }}" class="btn btn-success mr10">
                                            <i class="fa fa-plus mr10"></i>Create New
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15 search-container">
                                @if (auth()->user()->hasPrivilege('search-task'))
                                    {!! Form::jSearchForm($needle, route('task_search'), route('task_list')) !!}
                                @endif
                            </div>
                        </div>

                        <div class="">
                            <table class="table table-bordered list-table">
                                <thead>
                                    <tr>
                                        <th class="td-sortable">{!! SortableTrait::link('name', 'Name') !!}</th>
                                        <th class="td-sortable tc">{!! SortableTrait::link('tasks.created_by|users.first_name', 'Created By') !!}</th>
                                        <th class="td-sortable tc">{!! SortableTrait::link('tasks.assigned_to|users.first_name', 'Assigned To') !!}</th>
                                        <th class="td-sortable tc">{!! SortableTrait::link('due_at', 'Due') !!}</th>
                                        <th class="td-sortable tc">{!! SortableTrait::link('completed_at', 'Completed') !!}</th>
                                        <th class="actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="tbfs12">
                                    @foreach ($tasks as $task)
                                        <tr data-id="{{ $task->id }}" class="{{ !empty($task->disabled) ? 'disabled' : '' }}">
                                            <td class="">{{ str_limit($task->name, 50) }}</td>
                                            <td class="tc task-created-by">{!! $task->createdBy->fullName ?? '' !!}</td>
                                            <td class="tc task-assigned-to">{!! $task->assignedTo->fullName ?? '' !!}</td>
                                            <td class="tc task-due-at">{!! $task->html_due_at !!}</td>
                                            <td class="tc task-completed-at">{!! $task->html_completed_at !!}</td>
                                            <td class="centered actions all-time-visible-actions">
                                                @if (auth()->user()->hasPrivilege('show-task'))
                                                    <a href="javascript:void(0);" class="action show-details">View Details</a>
                                                @endif
                                                @if (auth()->user()->hasPrivilege('update-task'))
                                                    <a href="javascript:void(0);" class="action not-yet-available" data-action="route" data-route="{{ route('task_edit', ['id' => $task->id]) }}">Edit</a>
                                                @endif
                                                {{--
                                                @if (auth()->user()->hasPrivilege('disable-task'))
                                                    <a href="javascript:void(0);" class="action not-yet-available" data-action="route" data-route="{{ route('task_toggle_status', ['id' => $task->id]) }}">{{  empty($task->disabled) ? 'Disable' : 'Enable' }}</a>
                                                @endif
                                                @if (auth()->user()->hasPrivilege('delete-task'))
                                                    <a href="javascript:void(0);" class="action" data-action="delete" data-id="{{ $task->id }}">Delete</a>
                                                @endif
                                                --}}
                                                <div class="hidden">
                                                    <span class="task-name">{{ $task->name }}</span>
                                                    <span class="task-description">{{ $task->description }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {!! Form::jPaginator($tasks, 'task_list') !!}

                        {!! Form::jDeleteForm(route('task_delete')) !!}
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
                    <h4 class="modal-title">Task Details</h4>
                </div>
                <div class="modal-body jform-body pt20">
                    <div class="row">
                        <div class="col-sm-12 admin-form-item-widget">
                            <div class="show-wrapper">
                                {{ Form::jShow('&nbsp;', ['label' => 'Task', 'id' => 'modal_name', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-bookmark']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 admin-form-item-widget">
                            <div class="show-wrapper">
                                {{ Form::jShow('&nbsp;', ['label' => 'Due', 'id' => 'modal_due_at', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-calendar']) }}
                            </div>
                        </div>
                        <div class="col-sm-6 admin-form-item-widget">
                            <div class="show-wrapper">
                                {{ Form::jShow('&nbsp;', ['label' => 'Completed', 'id' => 'modal_completed_at', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-calendar']) }}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 admin-form-item-widget">
                            <div class="show-wrapper">
                                {{ Form::jShow('&nbsp;', ['label' => 'Created By', 'id' => 'modal_created_by', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-user']) }}
                            </div>
                        </div>
                        <div class="col-sm-6 admin-form-item-widget">
                            <div class="show-wrapper">
                                {{ Form::jShow('&nbsp;', ['label' => 'Assigned To', 'id' => 'modal_assigned_to', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-user']) }}
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

                $('#modal_name').html($row.find('.task-name').html());
                $('#modal_due_at').html($row.find('.task-due-at').html().replace('<br>', ' @ '));
                $('#modal_completed_at').html($row.find('.task-completed-at').html().replace('<br>', ' @ '));
                $('#modal_created_by').html($row.find('.task-created-by').html());
                $('#modal_assigned_to').html($row.find('.task-assigned-to').html());
                $('#modal_description').html($row.find('.task-description').html());

                $('#modal_show_details').modal('show');
            });
        });
    </script>
@stop
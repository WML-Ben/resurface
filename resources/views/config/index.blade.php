@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>System Settings</span></li>
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
                                    @if (auth()->user()->hasPrivilege('create-config'))
                                        <a href="{{ route('config_create') }}" class="btn btn-success mr10">
                                            <i class="fa fa-plus mr10"></i>Create New
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15 search-container">
                                @if (auth()->user()->hasPrivilege('search-config'))
                                    {!! Form::jSearchForm($needle, route('config_search'), route('config_list')) !!}
                                @endif
                            </div>
                        </div>

                        <div class="">
                            <table class="table table-bordered list-table">
                                <thead>
                                <tr>
                                    <th class="td-sortable">{!! SortableTrait::link('item_key', 'Item Identifier') !!}</th>
                                    <th class="td-sortable">{!! SortableTrait::link('item_value', 'Item Value') !!}</th>
                                    <th class="actions">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($conf as $item)
                                    <tr data-id="{{ $item->id }}" class="{{ !empty($item->disabled) ? 'disabled' : '' }}">
                                        @if (auth()->user()->hasPrivilege('modify-config'))
                                            <td>
                                                <span data-toggle="tooltip" title="Click to edit">
                                                    <a class="x-editable" href="#"
                                                       data-pk="{{ $item->id }}"
                                                       data-name="item_key"
                                                       data-value="{{ $item->item_key }}"
                                                       data-js-validation-function="isIdentifier"
                                                       data-js-validation-error-message="This field can only contain letters, numbers and underscore."
                                                       data-php-validation-rule="required|identifier|min:3|unique:config,item_key,{{ $item->id }}"
                                                       data-type="text"
                                                       data-title="Item Identifier:"
                                                       data-url="{{ route('config_inline_update') }}">
                                                       {{ $item->item_key }}
                                                    </a>
                                                </span>
                                            </td>
                                        @else
                                            <td>{{ $item->item_key }}</td>
                                        @endif
                                        @if (auth()->user()->hasPrivilege('update-config'))
                                            <td>
                                                <span data-toggle="tooltip" title="Click to edit">
                                                    <a class="x-editable" href="#"
                                                       data-pk="{{ $item->id }}"
                                                       data-name="item_value"
                                                       data-value="{{ $item->item_value }}"
                                                       data-js-validation-function="isText"
                                                       data-js-validation-error-message="Invalid entry."
                                                       data-php-validation-rule="text"
                                                       data-type="text"
                                                       data-title="Item Value:"
                                                       data-url="{{ route('config_inline_update') }}">
                                                        {{ $item->item_value }}
                                                    </a>
                                                </span>
                                            </td>
                                        @else
                                            <td>{{ $item->item_value }}</td>
                                        @endif
                                        <td class="centered actions">
                                            <ul class="nav navbar-nav">
                                                <li class="dropdown">
                                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                                    <ul class="dropdown-menu animated animated-short flipInX" role="menu">
                                                        @if (auth()->user()->hasPrivilege('update-config'))
                                                            <li>
                                                                <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('config_toggle_status', ['id' => $item->id]) }}">
                                                                    @if (empty($item->disabled))
                                                                        <span class="glyphicons glyphicons-ban"></span>Disable
                                                                    @else
                                                                        <span class="glyphicons glyphicons-ok"></span>Enable
                                                                    @endif
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if (auth()->user()->hasPrivilege('delete-config'))
                                                            <li class="menu-separator"></li>
                                                            <li>
                                                                <a href="javascript:void(0);" class="action" data-action="delete" data-id="{{ $item->id }}">
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

                        {!! Form::jPaginator($conf, 'config_list') !!}

                        {!! Form::jDeleteForm(route('config_delete')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


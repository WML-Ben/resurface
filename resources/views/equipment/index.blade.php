@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>Resources: Equipment</span></li>
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
                                    @if (auth()->user()->hasPrivilege('create-equipment'))
                                        <a href="{{ route('equipments_create') }}" class="btn btn-success mr10">
                                            <i class="fa fa-plus mr10"></i>Create New
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15 search-container">
                                @if (auth()->user()->hasPrivilege('search-equipment'))
                                    {!! Form::jSearchForm($needle, route('equipments_search'), route('equipments_list')) !!}
                                @endif
                            </div>
                        </div>

                        <div class="">
                            <table class="table table-bordered list-table">
                                <thead>
                                    <tr>
                                        <th class="td-sortable">{!! SortableTrait::link('name', 'Name') !!}</th>
                                        <th class="td-sortable tc">{!! SortableTrait::link('cost', 'Cost') !!}</th>
                                        <th class="td-sortable tc">{!! SortableTrait::link('min_cost', 'Min Cost') !!}</th>
                                        <th class="">Rate Type</th>
                                        <!-- <th class="actions">Actions</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($equipments as $equipment)
                                    <tr data-id="{{ $equipment->id }}" class="{{ !empty($equipment->disabled) ? 'disabled' : '' }}">
                                        <td>
                                            @if (auth()->user()->hasPrivilege('update-equipment'))
                                                <span title="Click to edit">
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $equipment->id }}"
                                                   data-name="name"
                                                   data-value="{{ $equipment->name }}"
                                                   data-js-validation-function="isPlainText"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule="required|plainText|unique:equipments,name,{{ $equipment->id }}"
                                                   data-type="text"
                                                   data-title="Name:"
                                                   data-url="{{ route('equipments_inline_update') }}">
                                                    {{ $equipment->name }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $equipment->name }}
                                            @endif
                                        </td>

                                        <td>
                                            @if (auth()->user()->hasPrivilege('update-equipment'))
                                                <span title="Click to edit">$
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $equipment->id }}"
                                                   data-name="cost"
                                                   data-value="{{ $equipment->cost_float }}"
                                                   data-js-validation-function="isCurrency"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule="required|currency"
                                                   data-type="text"
                                                   data-title="Cost:"
                                                   data-url="{{ route('equipments_inline_update') }}">
                                                    {{ $equipment->cost }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $equipment->cost_currency }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (auth()->user()->hasPrivilege('update-equipment'))
                                                <span title="Click to edit">$
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $equipment->id }}"
                                                   data-name="min_cost"
                                                   data-value="{{ $equipment->min_cost_float }}"
                                                   data-js-validation-function="isCurrency"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule="required|currency"
                                                   data-type="text"
                                                   data-title="Min Cost:"
                                                   data-url="{{ route('equipments_inline_update') }}">
                                                    {{ $equipment->min_cost }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $equipment->min_cost_currency }}
                                            @endif
                                        </td>

                                        <td>
                                            @if (auth()->user()->hasPrivilege('update-equipment'))
                                                <span title="Click to edit">
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $equipment->id }}"
                                                   data-name="rate_type_id"
                                                   data-value="{{ $equipment->rateType->name }}"
                                                   data-js-validation-function="isPlainText"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule=""
                                                   data-type="text"
                                                   data-title="Rate Type:"
                                                   data-url="{{ route('equipments_inline_update') }}">
                                                    {{ $equipment->rste_type_id }}
                                                </a>
                                            </span>
                                            @else
                                            {{ $equipment->rateType->name }}
                                            @endif
                                        </td>
<!--
                                        <td>

                                            @if (auth()->user()->hasPrivilege('update-equipment'))
                                                <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('equipments_edit', ['id' => $equipment->id]) }}">Edit</a>
                                            @endif
                                            @if (auth()->user()->hasPrivilege('delete-equipment'))
                                                <a href="javascript:;" class="action" data-action="delete" data-id="{{ $equipment->id }}">Delete</a>
                                            @endif
                                        </td>
    -->
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {!! Form::jPaginator($equipments, 'equipments_list') !!}

                        {!! Form::jDeleteForm(route('equipments_delete')) !!}
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

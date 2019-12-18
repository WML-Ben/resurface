@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>Categories</span></li>
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
                                    @if (auth()->user()->hasPrivilege('create-category'))
                                        <a href="{{ route('category_create') }}" class="btn btn-success mr10">
                                            <i class="fa fa-plus mr10"></i>Create New
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15 search-container">
                                @if (auth()->user()->hasPrivilege('search-category'))
                                    {!! Form::jSearchForm($needle, route('category_search'), route('category_list')) !!}
                                @endif
                            </div>
                        </div>

                        <div class="">
                            <table class="table table-bordered list-table">
                                <thead>
                                    <tr>
                                        <th class="td-sortable">{!! SortableTrait::link('name', 'Name') !!}</th>
                                        <th class="td-sortable tc td-name">{!! SortableTrait::link('categories.type_id|category_types.name', 'Type') !!}</th>
                                        <th class="td-sortable tc w120">{!! SortableTrait::link('do_not_delete', 'Do Not Delete') !!}</th>
                                        <th class="td-sortable tc w80">{!! SortableTrait::link('wizard', 'Wizard') !!}</th>
                                        <th class="actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($categories as $category)
                                    <tr data-id="{{ $category->id }}" class="{{ !empty($category->disabled) ? 'disabled' : '' }}">
                                        <td>
                                            @if (auth()->user()->hasPrivilege('update-category'))
                                                <span title="Click to edit">
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $category->id }}"
                                                   data-name="name"
                                                   data-value="{{ $category->name }}"
                                                   data-js-validation-function="isPlainText"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule="required|plainText|unique:categories,name,{{ $category->id }}"
                                                   data-type="text"
                                                   data-title="Category Name:"
                                                   data-url="{{ route('category_inline_update') }}">
                                                    {{ $category->name }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $category->name }}
                                            @endif
                                        </td>
                                        <td class="tc">
                                            @if (auth()->user()->hasPrivilege('update-category'))
                                                <span title="Click to edit">
                                                    <a class="x-editable" href="#"
                                                       data-pk="{{ $category->id }}"
                                                       data-name="type_id"
                                                       data-value="{{ $category->type_id }}"
                                                       data-source="{{ $json_category_types_cb }}"
                                                       data-js-validation-function="isPositive"
                                                       data-js-validation-error-message="Invalid type."
                                                       data-php-validation-rule="positive"
                                                       data-type="select"
                                                       data-title="Type:"
                                                       data-url="{{ route('category_inline_update') }}">
                                                       {{ $category->type->name ?? 'unknown' }}
                                                    </a>
                                                </span>
                                            @else
                                                {{ $category->type->name ?? 'unknown' }}
                                            @endif
                                        </td>
                                        <td class="tc">
                                            @if (auth()->user()->hasPrivilege('update-category'))
                                                <span title="Click to edit">
                                                    <a class="x-editable" href="#"
                                                       data-pk="{{ $category->id }}"
                                                       data-name="do_not_delete"
                                                       data-value="{{ $category->do_not_delete }}"
                                                       data-source="{{ $json_yes_no_cb }}"
                                                       data-js-validation-function="isBoolean"
                                                       data-js-validation-error-message="Invalid type."
                                                       data-php-validation-rule="boolean"
                                                       data-type="select"
                                                       data-title="Do Not Delete:"
                                                       data-url="{{ route('category_inline_update') }}">
                                                       {{ !empty($category->do_not_delete) ? 'Yes' : 'No' }}
                                                    </a>
                                                </span>
                                            @else
                                                {{ !empty($category->do_not_delete) ? 'Yes' : 'No' }}
                                            @endif
                                        </td>
                                        <td class="tc">
                                            @if (auth()->user()->hasPrivilege('update-category'))
                                                <span title="Click to edit">
                                                    <a class="x-editable" href="#"
                                                       data-pk="{{ $category->id }}"
                                                       data-name="wizard"
                                                       data-value="{{ $category->wizard }}"
                                                       data-source="{{ $json_yes_no_cb }}"
                                                       data-js-validation-function="isBoolean"
                                                       data-js-validation-error-message="Invalid type."
                                                       data-php-validation-rule="boolean"
                                                       data-type="select"
                                                       data-title="Wizard:"
                                                       data-url="{{ route('category_inline_update') }}">
                                                       {{ !empty($category->wizard) ? 'Yes' : 'No' }}
                                                    </a>
                                                </span>
                                            @else
                                                {{ !empty($category->wizard) ? 'Yes' : 'No' }}
                                            @endif
                                        </td>
                                        <td class="centered actions">
                                            <ul class="nav navbar-nav">
                                                <li class="dropdown">
                                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                                    <ul class="dropdown-menu animated animated-short flipInX" role="menu">
                                                        @if (auth()->user()->hasPrivilege('update-category'))
                                                            <li>
                                                                <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('category_edit', ['id' => $category->id]) }}">
                                                                    <span class="fa fa-edit mr8"></span>Edit
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if (auth()->user()->hasPrivilege('delete-category'))
                                                            <li class="menu-separator"></li>
                                                            <li>
                                                                <a href="javascript:void(0);" class="action" data-action="delete" data-id="{{ $category->id }}">
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

                        {!! Form::jPaginator($categories, 'category_list') !!}

                        {!! Form::jDeleteForm(route('category_delete')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>Contact Categories</span></li>
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
                                    @if (auth()->user()->hasPrivilege('create-user-category'))
                                        <a href="{{ route('user_category_create') }}" class="btn btn-success mr10">
                                            <i class="fa fa-plus mr10"></i>Create New
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15 search-container">
                                @if (auth()->user()->hasPrivilege('search-user-category'))
                                    {!! Form::jSearchForm($needle, route('user_category_search'), route('user_category_list')) !!}
                                @endif
                            </div>
                        </div>

                        <div class="">
                            <table class="table table-bordered list-table">
                                <thead>
                                    <tr>
                                        <th class="td-sortable w250">{!! SortableTrait::link('name', 'Name') !!}</th>
                                        <th class="xs-hidden td-sortable">{!! SortableTrait::link('description', 'Description') !!}</th>
                                        <th class="actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($userCategories as $userCategory)
                                    <tr data-id="{{ $userCategory->id }}" class="{{ !empty($userCategory->disabled) ? 'disabled' : '' }}">
                                        <td>
                                            @if (auth()->user()->hasPrivilege('update-user-category'))
                                                <span title="Click to edit">
                                                    <a class="x-editable" href="#"
                                                       data-pk="{{ $userCategory->id }}"
                                                       data-name="name"
                                                       data-value="{{ $userCategory->name }}"
                                                       data-js-validation-function="isPlainText"
                                                       data-js-validation-error-message="Invalid entry"
                                                       data-php-validation-rule="required|plainText|unique:user_categories,name,{{ $userCategory->id }}"
                                                       data-type="text"
                                                       data-title="Name:"
                                                       data-url="{{ route('user_category_inline_update') }}">
                                                        {{ $userCategory->name }}
                                                    </a>
                                                </span>
                                            @else
                                                {{ $userCategory->name }}
                                            @endif
                                        </td>
                                        <td class="xs-hidden">
                                            @if (auth()->user()->hasPrivilege('update-user-category'))
                                                <span title="Click to edit">
                                                    <a class="x-editable" href="#"
                                                       data-pk="{{ $userCategory->id }}"
                                                       data-name="description"
                                                       data-value="{{ $userCategory->description }}"
                                                       data-js-validation-function="isPlainText"
                                                       data-js-validation-error-message="Invalid entry"
                                                       data-php-validation-rule="plainText"
                                                       data-type="textarea"
                                                       data-title="Description:"
                                                       data-url="{{ route('user_category_inline_update') }}">
                                                        {{ $userCategory->description }}
                                                    </a>
                                                </span>
                                            @else
                                                {{ $userCategory->description }}
                                            @endif
                                        </td>
                                        <td class="centered actions">
                                            <ul class="nav navbar-nav">
                                                <li class="dropdown">
                                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                                    <ul class="dropdown-menu animated animated-short flipInX" role="menu">
                                                        @if (auth()->user()->hasPrivilege('update-user-category'))
                                                            <li>
                                                                <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('user_category_edit', ['id' => $userCategory->id]) }}">
                                                                    <span class="fa fa-edit mr8"></span>Edit
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if (auth()->user()->hasPrivilege('delete-user-category'))
                                                            <li class="menu-separator"></li>
                                                            <li>
                                                                <a href="javascript:void(0);" class="action" data-action="delete" data-id="{{ $userCategory->id }}">
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

                        {!! Form::jPaginator($userCategories, 'user_category_list') !!}

                        {!! Form::jDeleteForm(route('user_category_delete')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


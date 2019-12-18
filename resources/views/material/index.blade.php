@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>Resources: Materials Cost</span></li>
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
                                    @if (auth()->user()->hasPrivilege('create-material'))
                                        <a href="{{ route('materials_create') }}" class="btn btn-success mr10">
                                            <i class="fa fa-plus mr10"></i>Create New
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15 search-container">
                                @if (auth()->user()->hasPrivilege('search-material'))
                                    {!! Form::jSearchForm($needle, route('materials_search'), route('materials_list')) !!}
                                @endif
                            </div>
                        </div>

                        <div class="">
                            <table class="table table-bordered list-table">
                                <thead>
                                    <tr>
                                        <th class="td-sortable">{!! SortableTrait::link('name', 'Name') !!}</th>
                                        <th class="td-sortable tc">{!! SortableTrait::link('cost', 'Cost') !!}</th>
                                        <th class="td-sortable tc">{!! SortableTrait::link('alt_cost', 'Alt Cost') !!}</th>
<!--                                        <th class="actions">Actions</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($materials as $material)
                                    <tr data-id="{{ $material->id }}" class="{{ !empty($material->disabled) ? 'disabled' : '' }}">
                                        <td>
                                            @if (auth()->user()->hasPrivilege('update-material'))
                                                <span title="Click to edit">
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $material->id }}"
                                                   data-name="name"
                                                   data-value="{{ $material->name }}"
                                                   data-js-validation-function="isPlainText"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule="required|plainText|unique:materials,name,{{ $material->id }}"
                                                   data-type="text"
                                                   data-title="Name:"
                                                   data-url="{{ route('materials_inline_update') }}">
                                                    {{ $material->name }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $material->name }}
                                            @endif
                                        </td>

                                        <td>
                                            @if (auth()->user()->hasPrivilege('update-material'))
                                                <span title="Click to edit">$
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $material->id }}"
                                                   data-name="cost"
                                                   data-value="{{ $material->cost_float }}"
                                                   data-js-validation-function="isCurrency"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule="required|currency"
                                                   data-type="text"
                                                   data-title="Cost:"
                                                   data-url="{{ route('materials_inline_update') }}">
                                                    {{ $material->cost_float }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $material->cost_currency }}
                                            @endif
                                        </td>
                                        <td>
                                            @if (auth()->user()->hasPrivilege('update-material'))
                                                <span title="Click to edit">$
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $material->id }}"
                                                   data-name="alt_cost"
                                                   data-value="{{ $material->alt_cost_float }}"
                                                   data-js-validation-function="isCurrency"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule="required|currency"
                                                   data-type="text"
                                                   data-title="Alt Cost:"
                                                   data-url="{{ route('materials_inline_update') }}">
                                                    {{ $material->alt_cost_float }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $material->alt_cost_currency }}
                                            @endif
                                        </td>

<!--                                        <td class="actions all-time-visible-actions">

                                            @if (auth()->user()->hasPrivilege('update-material'))
                                                <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('materials_edit', ['id' => $material->id]) }}">Edit</a>
                                            @endif
                                            @if (auth()->user()->hasPrivilege('delete-material'))
                                                <a href="javascript:;" class="action" data-action="delete" data-id="{{ $material->id }}">Delete</a>
                                            @endif
-->
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {!! Form::jPaginator($materials, 'materials_list') !!}

                        {!! Form::jDeleteForm(route('materials_delete')) !!}
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

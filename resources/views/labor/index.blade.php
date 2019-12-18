@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>Resources:Labor Types</span></li>
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
                                    @if (auth()->user()->hasPrivilege('create-labor'))
                                        <a href="{{ route('labor_create') }}" class="btn btn-success mr10">
                                            <i class="fa fa-plus mr10"></i>Create New
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15 search-container">
                                @if (auth()->user()->hasPrivilege('search-labor'))
                                    {!! Form::jSearchForm($needle, route('labor_search'), route('labor_list')) !!}
                                @endif
                            </div>
                        </div>

                        <div class="">
                            <table class="table table-bordered list-table">
                                <thead>
                                    <tr>
                                        <th class="td-sortable">{!! SortableTrait::link('name', 'Name') !!}</th>
                                        <th class="actions">Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($labor as $labors)
                                    <tr data-id="{{ $labors->id }}" class="{{ !empty($labors->disabled) ? 'disabled' : '' }}">
                                        <td>
                                            @if (true || auth()->user()->hasPrivilege('update-labor'))
                                                <span title="Click to edit">
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $labors->id }}"
                                                   data-name="name"
                                                   data-value="{{ $labors->name }}"
                                                   data-js-validation-function="isPlainText"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule="required|plainText|unique:labor,name,{{ $labors->id }}"
                                                   data-type="text"
                                                   data-title="Name:"
                                                   data-url="{{ route('labor_inline_update') }}">
                                                    {{ $labors->name }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $labors->name }}
                                            @endif
                                        </td>

                                        <td>
                                            @if (true || auth()->user()->hasPrivilege('update-labor'))
                                                <span title="Click to edit">
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $labors->id }}"
                                                   data-name="rate"
                                                   data-value="{{ $labors->rate }}"
                                                   data-js-validation-function="isCurrency"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule="required|currency|labor,rate,{{ $labors->id }}"
                                                   data-type="text"
                                                   data-title="Rate:"
                                                   data-url="{{ route('labor_inline_update') }}">
                                                    {{ $labors->rate }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $labors->rate }}
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {!! Form::jPaginator($labor, 'labor_list') !!}

                        {!! Form::jDeleteForm(route('labor_delete')) !!}
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

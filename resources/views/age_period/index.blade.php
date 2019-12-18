@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>Age Periods</span></li>
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
                                    @if (auth()->user()->hasPrivilege('create-age-period'))
                                        <a href="{{ route('age_period_create') }}" class="btn btn-success mr10">
                                            <i class="fa fa-plus mr10"></i>Create New
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15 search-container">
                                @if (auth()->user()->hasPrivilege('search-age-period'))
                                    {!! Form::jSearchForm($needle, route('age_period_search'), route('age_period_list')) !!}
                                @endif
                            </div>
                        </div>

                        <div class="">
                            <table class="table table-bordered list-table">
                                <thead>
                                    <tr>
                                        <th class="td-sortable">{!! SortableTrait::link('name', 'Name') !!}</th>
                                        <th class="td-sortable tc w100">{!! SortableTrait::link('initial_day', 'Initial Day') !!}</th>
                                        <th class="td-sortable tc w100">{!! SortableTrait::link('final_day', 'Final Day') !!}</th>
                                        <th class="tc w80">Icon</th>
                                        <th class="td-sortable tc w100">{!! SortableTrait::link('icon_class', 'Icon Class') !!}</th>
                                        <th class="td-sortable tc w100">{!! SortableTrait::link('icon_color', 'Icon Color') !!}</th>
                                        <th class="actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($agePeriods as $agePeriod)
                                    <tr data-id="{{ $agePeriod->id }}" class="{{ !empty($agePeriod->disabled) ? 'disabled' : '' }}">
                                        <td>
                                            @if (auth()->user()->hasPrivilege('update-age-period'))
                                                <span title="Click to edit">
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $agePeriod->id }}"
                                                   data-name="name"
                                                   data-value="{{ $agePeriod->name }}"
                                                   data-js-validation-function="isPlainText"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule="required|plainText|unique:age_periods,name,{{ $agePeriod->id }}"
                                                   data-type="text"
                                                   data-title="Period Name:"
                                                   data-url="{{ route('age_period_inline_update') }}">
                                                    {{ $agePeriod->name }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $agePeriod->name }}
                                            @endif
                                        </td>
                                        <td class="tc">
                                            @if (auth()->user()->hasPrivilege('update-age-period'))
                                                <span title="Click to edit">
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $agePeriod->id }}"
                                                   data-name="initial_day"
                                                   data-value="{{ $agePeriod->initial_day }}"
                                                   data-js-validation-function="isPositive"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule="nullable|positive"
                                                   data-type="text"
                                                   data-title="Initial Day:"
                                                   data-url="{{ route('age_period_inline_update') }}">
                                                    {{ $agePeriod->initial_day }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $agePeriod->initial_day }}
                                            @endif
                                        </td>
                                        <td class="tc">
                                            @if (auth()->user()->hasPrivilege('update-age-period'))
                                                <span title="Click to edit">
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $agePeriod->id }}"
                                                   data-name="final_day"
                                                   data-value="{{ $agePeriod->final_day }}"
                                                   data-js-validation-function="isPositive"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule="nullable|positive"
                                                   data-type="text"
                                                   data-title="Final Day:"
                                                   data-url="{{ route('age_period_inline_update') }}">
                                                    {{ $agePeriod->final_day }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $agePeriod->final_day }}
                                            @endif
                                        </td>
                                        <td class="tc td-icon"><i class="{{ $agePeriod->icon_class ?? '' }}"{!! !empty($agePeriod->icon_color) ? ' style="color:'. $agePeriod->icon_color .'"' : '' !!}></i></td>
                                        <td class="tc">
                                            @if (auth()->user()->hasPrivilege('update-age-period'))
                                                <span title="Click to edit">
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $agePeriod->id }}"
                                                   data-name="icon_class"
                                                   data-value="{{ $agePeriod->icon_class }}"
                                                   data-js-validation-function="isPlainText"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule="nullable|plainText"
                                                   data-type="text"
                                                   data-title="Icon Class:"
                                                   data-url="{{ route('age_period_inline_update') }}">
                                                    {{ $agePeriod->icon_class }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $agePeriod->icon_class }}
                                            @endif
                                        </td>
                                        <td class="tc">
                                            @if (auth()->user()->hasPrivilege('update-age-period'))
                                                <span title="Click to edit">
                                                <a class="x-editable" href="#"
                                                   data-pk="{{ $agePeriod->id }}"
                                                   data-name="icon_color"
                                                   data-value="{{ $agePeriod->icon_color }}"
                                                   data-js-validation-function="isPlainText"
                                                   data-js-validation-error-message="Invalid entry"
                                                   data-php-validation-rule="nullable|plainText"
                                                   data-type="text"
                                                   data-title="Icon Color:"
                                                   data-url="{{ route('age_period_inline_update') }}">
                                                    {{ $agePeriod->icon_color }}
                                                </a>
                                            </span>
                                            @else
                                                {{ $agePeriod->icon_color }}
                                            @endif
                                        </td>
                                        <td class="centered actions">
                                            <ul class="nav navbar-nav">
                                                <li class="dropdown">
                                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                                    <ul class="dropdown-menu animated animated-short flipInX" role="menu">
                                                        @if (auth()->user()->hasPrivilege('update-age-period'))
                                                            <li>
                                                                <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('age_period_edit', ['id' => $agePeriod->id]) }}">
                                                                    <span class="fa fa-edit mr8"></span>Edit
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if (auth()->user()->hasPrivilege('delete-age-period'))
                                                            <li class="menu-separator"></li>
                                                            <li>
                                                                <a href="javascript:void(0);" class="action" data-action="delete" data-id="{{ $agePeriod->id }}">
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

                        {!! Form::jPaginator($agePeriods, 'age_period_list') !!}

                        {!! Form::jDeleteForm(route('age_period_delete')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js-files')
    <script>
        function xeditCallback(el, response, newValue)
        {
            if (response.field == 'icon_class') {
                el.parents('tr').find('.td-icon i').removeClass().addClass(newValue);
            } else if (response.field == 'icon_color') {


                el.parents('tr').find('.td-icon i').attr('style', 'color:' + newValue);
            }
        }
    </script>
@stop

@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>Resources:Company Locations</span></li>
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
                                    @if (auth()->user()->hasPrivilege('create-location'))
                                        <a href="{{ route('location_create') }}" class="btn btn-success mr10">
                                            <i class="fa fa-plus mr10"></i>Create New
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15 search-container">
                                @if (auth()->user()->hasPrivilege('search-location'))
                                    {!! Form::jSearchForm($needle, route('location_search'), route('location_list')) !!}
                                @endif
                            </div>
                        </div>

                        <div class="">
                            <table class="table table-bordered list-table">
                                <thead>
                                    <tr>
                                        <th class="td-sortable">{!! SortableTrait::link('name', 'Name') !!}</th>
                                        <th class="">Address</th>
                                        <th class="">Actions</th>

                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($location as $locations)
                                    <tr data-id="{{ $locations->id }}" class="{{ !empty($locations->disabled) ? 'disabled' : '' }}">
                                        <td>
                                                {{ $locations->name }}
                                        </td>

                                        <td>
                                            {{ $locations->address }}
                                            {{ $locations->address2 }}
                                            {{ $locations->city}}
                                            {{ $locations->state->name}},
                                            {{ $locations->zipcode}}
                                        </td>

                                        <td class="centered actions all-time-visible-actions">
                                            @if (auth()->user()->hasPrivilege('update-location'))
                                                <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('location_edit', ['id' => $locations->id]) }}">Edit</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {!! Form::jPaginator($location, 'location_list') !!}

                        {!! Form::jDeleteForm(route('location_delete')) !!}
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

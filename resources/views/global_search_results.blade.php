@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>Global Search Results</span></li>
    </ul>
@stop

@section('content')
    <div class="page-content-inner">
        <div class="row">
            <div class="col-md-12">
                @include('errors._list')
                <div class="portlet box list-items admin-form">
                    <div class="portlet-body">
                        <div class="table-responsive">
                            <table class="table table-bordered list-table">
                                <thead>
                                    <tr>
                                        <th class="td-sortable">{!! SortableTrait::link('name', 'Name') !!}</th>
                                        <th class="td-sortable tc">{!! SortableTrait::link('email', 'Email') !!}</th>
                                        <th class="td-sortable tc">{!! SortableTrait::link('phone', 'Phone') !!}</th>
                                        <th class="td-sortable tc">{!! SortableTrait::link('type', 'Type') !!}</th>
                                        <th class="actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($globalSearchResults as $globalSearchResult)
                                    <tr data-id="{{ $globalSearchResult->type }}_{{ $globalSearchResult->id }}">
                                        <td>{{ $globalSearchResult->name }}</td>
                                        <td class="tc">{{ $globalSearchResult->email }}</td>
                                        <td class="tc">{{ $globalSearchResult->phone }}</td>
                                        <td class="tc">{{ $globalSearchResult->type }}</td>
                                        <td class="centered actions">
                                            <ul class="nav navbar-nav">
                                                <li class="dropdown">
                                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                                    <ul class="dropdown-menu animated animated-short flipInX" role="menu">
                                                        @if (auth()->user()->hasPrivilege('delete-privilege'))
                                                            <li>
                                                                <a href="{{ route( strtolower($globalSearchResult->type) . '_show', ['id' => $globalSearchResult->id]) }}" class="not-yet-available action">
                                                                    <span class="fa fa-eye mr8"></span>View
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

                        {!! Form::jPaginator($globalSearchResults, 'global_search') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


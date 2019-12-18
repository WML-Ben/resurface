@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>AP Employees</span></li>
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
                                    @if (auth()->user()->hasPrivilege('create-employee'))
                                        <a href="{{ route('employee_create') }}" class="btn btn-success mr10">
                                            <i class="fa fa-plus mr10"></i>Add New
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15 search-container">
                                @if (auth()->user()->hasPrivilege('search-employee'))
                                    {!! Form::jSearchForm($needle, route('employee_search'), route('employee_list')) !!}
                                @endif
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered list-table">
                                <thead>

                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>

                        {!! Form::jPaginator($employees, 'employee_list') !!}

                        {!! Form::jDeleteForm(route('employee_delete')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js-files')
    <script>
        $(function(){

        });
    </script>
@stop

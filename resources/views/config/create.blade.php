@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><a href="{{ route('config_list') }}">System Settings</a><i class="fa fa-angle-right"></i></li>
            <li><span>Create New</span></li>
        </ul>
    </div>
@stop

@section('content')
    <section id="content" class="animated fadeIn list-items admin-form">
        <div class="row">
            <div class="col-md-9 center-block">
                @include('errors._list')
                <div class="admin-form theme-primary">
                    <div class="panel">
                        {!! Form::open(['url' => route('config_store'), 'id' => 'createConfigForm']) !!}
                            @include('config._form', ['formTitle' => 'New Item', 'submitButtonText' => 'Create Item'])
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('js-files')
    <script>
        $(function () {
            $('#createConfigForm').validate({
                rules: {
                    item_key: {
                        required  : true,
                        identifier: true,
                        minlength : 3
                    },
                    item_value: {
                        required: false,
                        text    : true
                    }
                },
                messages: {
                    item_key: {
                        identifier: 'This field can only contain letters, numbers and underscore.',
                        minlength : 'Item key must has at least 3 chars.'
                    }
                }
            });

            $('#cancel-button').click(function (ev) {
                ev.preventDefault();
                window.location = "{{ route('config_list') }}";
            });

        });
    </script>
@stop
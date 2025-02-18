@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><a href="{{ route('employee_list') }}">Resources:Vehicles</a><i class="fa fa-angle-right"></i></li>
            <li><span>Add New</span></li>
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
                        {!! Form::open(['url' => route('vehicle_store'), 'id' => 'createForm']) !!}
                            @include('vehicle._form', ['formTitle' => 'New Vehicle', 'submitButtonText' => 'Add'])
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('js-files')
    <script>
        $(function(){
            $('#createForm').validate({
                rules: {
                    // required fields:
                    name: {
                        required : true,
                        plainText: true
                    },
                    type_id: {
                        required: true,
                        positive : true
                    },
                    location_id: {
                        required: true,
                        positive: true
                    },
                    // non required fields:
                    description: {
                        required : false,
                        plainText: true
                    },
                    purchased_at: {
                        required: false,
                        usDate  : true
                    }
                },
                messages: {
                    type_id: {
                        required: 'Please, select a type.',
                        positive: 'Please, select a type.'
                    },
                    location_id: {
                        required: 'Please, select a location.',
                        positive: 'Please, select a location.'
                    }
                }
            });

            $('#cancel-button').click(function(ev){
                ev.preventDefault();
                window.location = "{{ route('vehicle_list') }}";
            });
        });

    </script>
@stop
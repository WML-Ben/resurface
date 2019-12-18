@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><a href="{{ route('vehicle_type_list') }}">Resources: Vehicle Types</a><i class="fa fa-angle-right"></i></li>
            <li><span>Edit</span></li>
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
                        {!! Form::model($vehicleType, ['url' => route('vehicle_type_update', ['id' => $vehicleType->id]), 'method' => 'PATCH', 'id' => 'updateForm']) !!}
                            {!! Form::hidden('id', $vehicleType->id) !!}
                            @include('vehicle_type._form', ['formTitle' => 'Update Vehicle Types', 'submitButtonText' => 'Update'])
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
            $('#updateForm').validate({
                rules: {
                    // required fields
                    name: {
                        required : true,
                        plainText: true
                    },
                    rate: {
                        required: true,
                        currency: true
                    },
                    // no required fields
                    description: {
                        required : false,
                        plainText: true
                    }
                }
            });

            $('#cancel-button').click(function(ev){
                ev.preventDefault();
                window.location = "{{ route('vehicle_type_list') }}";
            });
        });
    </script>
@stop
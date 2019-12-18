@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><a href="{{ route('materials_list') }}">Resources: Materials</a><i class="fa fa-angle-right"></i></li>
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
                        {!! Form::open(['url' => route('materials_store'), 'id' => 'createForm']) !!}
                            @include('material._form', ['formTitle' => 'New Material', 'submitButtonText' => 'Create New Material', 'create' => true])
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
                    // required fields
                    name: {
                        required : true,
                        plainText: true
                    },
                    cost: {
                        required: true,
                        currency: true
                    },
                    alt_cost: {
                        required: true,
                        currency: true
                    },

                }
            });

            $('#cancel-button').click(function(ev){
                ev.preventDefault();
                window.location = "{{ route('materials_list') }}";
            });
        });
    </script>
@stop
@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><a href="{{ route('category_list') }}">Categories</a><i class="fa fa-angle-right"></i></li>
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
                        {!! Form::model($category, ['url' => route('category_update', ['id' => $category->id]), 'method' => 'PATCH', 'id' => 'updateForm']) !!}
                            {!! Form::hidden('id', $category->id) !!}
                            @include('category._form', ['formTitle' => 'Update Category', 'roleId' => $category->role_id, 'submitButtonText' => 'Update'])
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
                    name: {
                        required : true,
                        plainText: true
                    },
                    type_id: {
                        required: true,
                        positive: true
                    },
                    description: {
                        required : false,
                        text     : true
                    }
                }
            });

            $('#cancel-button').click(function(ev){
                ev.preventDefault();
                window.location = "{{ route('category_list') }}";
            });
        });
    </script>
@stop
@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><a href="{{ route('age_period_list') }}">Age Periods</a><i class="fa fa-angle-right"></i></li>
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
                        {!! Form::model($agePeriod, ['url' => route('age_period_update', ['id' => $agePeriod->id]), 'method' => 'PATCH', 'id' => 'updateForm']) !!}
                            {!! Form::hidden('id', $agePeriod->id) !!}
                            @include('age_period._form', ['formTitle' => 'Update Age Period', 'submitButtonText' => 'Update'])
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
                    initial_day: {
                        required: false,
                        positive: true
                    },
                    final_day: {
                        required: false,
                        positive: true
                    },
                    icon_class: {
                        required : false,
                        plainText: true
                    },
                    icon_color: {
                        required : false,
                        plainText: true
                    }
                }
            });

            $('#cancel-button').click(function(ev){
                ev.preventDefault();
                window.location = "{{ route('age_period_list') }}";
            });
        });
    </script>
@stop
@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><a href="{{route('edit-email-template')}}">Template Email</a><i class="fa fa-angle-right"></i></li>
            <li><span>Create New</span></li>
        </ul>
    </div>
@stop

@section('content')
    <section id="content" class="animated fadeIn admin-form">
        <div class="row">
            <div class="col-md-9 center-block">
                @include('errors._list')
                <div class="admin-form theme-primary">
                    <div class="panel">
                        {!! Form::open(['url' => route('store_email_template'), 'id' => 'createForm']) !!}
                            <div class="panel-body">
								<div class="section-divider mb40 mt20"><span>Create New Email Template</span></div>
								<div class="row">
									<div class="col-md-12 admin-form-item-widget">
										{{ Form::jText('name', ['label' => 'Name', 'id' => 'name', 'placeholder' => '', 'required' => true]) }}
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 admin-form-item-widget">
									<span class="form-field-label">Content Email:<i class="field-required fa fa-asterisk"></i></span>
										 <textarea name="content_send_email" id="summernote_1"></textarea>
									</div>
								</div>	
							</div>
							<div class="panel-footer text-right">
								<div class="row">
									<div class="col-sm-12">
										{{ Form::jCancelSubmit(['submit-label' => 'Create Email Template']) }}
									</div>
								</div>
							</div>
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
                    name: {
                        required : true,
                        plainText: true
                    }
                }
            });

            $('#cancel-button').click(function(ev){
                ev.preventDefault();
                window.location = "{{ route('dashboard') }}";
            });
        });
    </script>
@stop
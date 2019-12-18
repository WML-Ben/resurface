@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><a href="{{route('edit-email-template')}}">Template Email</a><i class="fa fa-angle-right"></i></li>
            <li><span>Edit</span></li>
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
                        {!! Form::open(['url' => route('update_email_template'), 'id' => 'createForm']) !!}
                            <div class="panel-body">
								<div class="section-divider mb40 mt20"><span>Edit Email Template</span></div>
								<div class="row">
									<div class="col-md-12 admin-form-item-widget">
										<span class="top-right-field-link">
                                           <a class="green" href="{{route('email_template_create')}}">New</a>
                                        </span>
										<span class="form-field-label">Choose Template Email <i class="field-required fa fa-asterisk"></i></span>
										<select id="id_choose_template" name="id_choose_template" class="form-control">
											<option value="0">Choose Template Email</option>
											@foreach($email_template as $item)
												<option value="{{$item->id}}">{{$item->subject}}</option>
											@endforeach
										</select>
										<input id="id-email" type="hidden" name="id_email" value="">
									</div>
								</div>
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
<style>
.green{
	color:#1f9950 !important;
}
.field-icon{
	top:14px !important;
}
</style>
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
			$('#id_choose_template').change(function(){
			$("#summernote_1").summernote("reset");
				var template_email = $('#id_choose_template').val();
				
				if(template_email !== '0')
				{
					var name = $('#id_choose_template option:selected').text();
					$.ajax({
						  url:"{{ route('ajax_choose_lead_email_template') }}",
						  data:{
							id_template: template_email,
						  },
						   beforeSend: function (request){
							PNotify.removeAll();
							showSpinner();
						  },
						  complete: function(){
							hideSpinner();
						  },
						  dataType:'text',
						  type:'post',
						  success:function(response){
							 $('#name').val(name);
							 $('#id-email').val(template_email);
							 $('#summernote_1').summernote('code', response);
						},
					});
				}
				else
				{
					 $('#name').val('');
					 $('#id-email').val('');
				}
			});
        });
    </script>
@stop
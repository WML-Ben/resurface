@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><span>Intake Form</span></li>
        </ul>
    </div>
@stop

@section('content')
    <section id="content" class="animated fadeIn admin-form">
        <div class="row intake-form">
            <div class="col-md-9 center-block">
                @include('errors._list')
                <div class="admin-form theme-primary">
				<form action="{{route('store_intake_form')}}" method="post" id="intake" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="_token"  value="{{ csrf_token() }}" />
					<div class="panel">
						<div class="panel-body">
							<div id="new-customer">
								<h2  class="form-section-divider mt0">Property/Company</h2>
								<div class="row">
									<div class="col-md-6">
										<div class="inner-field">
											{{ Form::jSelect2('new_property_id', [], ['label' => 'Communities/ Property Name', 'selected' => null, 'required' => true, 'iconClass' => 'fa fa-building', 'attributes' => ['id' => 'new_intake_property_id_add']]) }}
											<input id="intake_property_id" class="gui-input"  placeholder="" name="properties_id" type="hidden">
										</div>
			   
											<div class="inner-field"><span class="form-field-label">Address</span><input readonly id="intake_address_property" name="address_properties" type="text" class="form-control" placeholder="Enter Address"></div>

											<div class="inner-field"><input readonly id="intake_city_property" name="city" type="text" class="form-control" placeholder="Enter City"></div>

											<div class="inner-field"><input readonly id="intake_state_property" name="state" type="text" class="form-control" placeholder="Enter State"></div>

											<div class="inner-field"><input readonly id="intake_zipcode_property" name="zipcode" type="text" class="form-control" placeholder="Enter Zip"></div>
										
									</div>

									<div class="col-md-6">
										<div class="inner-field" style="margin-bottom:14px;">
											<span class="form-field-label">Company</span>
											<label for="intake_phone_number" class="field prepend-icon">
												<input id="intake_company_name" readonly class="gui-input"  placeholder="" name="company" type="text">
												<input id="intake_company_id" class="gui-input"  placeholder="" name="id_company" type="hidden">
												<span class="field-icon"><i class="fa fa-building"></i></span>
											</label>
										</div>
										<div class="inner-field">
											<span class="form-field-label">Address</span><input readonly id="intake_address_company" name="street_address_com" type="text" class="form-control" placeholder="Enter Address">
										</div>

										<div class="inner-field"><input readonly id="intake_company_city" name="city_com" type="text" class="form-control" placeholder="Enter City"></div>

										<div class="inner-field"><input readonly id="intake_company_state" name="state_com" type="text" class="form-control" placeholder="Enter State"></div>

										<div class="inner-field"><input readonly id="intake_company_zipcode" name="zipcode_com" type="text" class="form-control" placeholder="Enter Zip"></div>

									</div>
									<!--<div class="col-md-12"><img src="assets/img/map.png" class="map-image"></div>-->
								</div>
								<hr/>
								<h2  class="form-section-divider mt0">Lead Information</h2>
								<div class="row">
									<div class="col-md-12">
										<label class="control-label">Date</label>
										<div style="width:100%;margin-bottom:5px;" class="input-group f date form_meridian_datetime">
											<input  value="<?php echo date('m/d/Y',time()); ?>" name="date_intake" type="text" size="16" class="form-control intake-date">
											<!--<span class="input-group-btn">
													<button class="btn default date-set" type="button">
														<i class="fa fa-calendar"></i>
													</button>
												</span>-->
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6">
										<input id="intake_contact_id" class="gui-input"  placeholder="" name="contact_id" type="hidden">
										{{ Form::jText('firstname', ['label' => 'First Name', 'id' => 'intake_firstname', 'placeholder' => '', 'required' => true, 'iconClass' => 'fa fa-user']) }}
									</div>
									<div class="col-md-6">
									   {{ Form::jText('lastname', ['label' => 'Last Name', 'id' => 'intake_lastname', 'placeholder' => '', 'required' => true, 'iconClass' => 'fa fa-user']) }}
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										{{ Form::jText('phone', ['label' => 'Phone', 'id' => 'intake_phone_number', 'placeholder' => '', 'required' => true, 'iconClass' => 'fa fa-phone']) }}
									</div>
									<div class="col-md-6">
										{{ Form::jText('email', ['label' => 'Email', 'id' => 'intake_email', 'required' => true, 'iconClass' => 'fa fa-envelope']) }}
									</div>
								</div>
								<hr>
								<h2 class="form-section-divider">Questions</h2>
								<div class="row">
									<div class="col-md-6 service-container">
										<div class="inner-field">{{ Form::jMultiSelect('service_id', $serviceCateogry['serviceCateogry'], ['label' => 'What Services were you looking for?', 'id' => 'intake_service_category_ids', 'required' => false, 'iconClass' => 'icon-wrench-1']) }}</div>
										<div class="if_other">
											{{ Form::jText('service_other_des', ['label' => 'Other Services', 'id' => 'other_services', 'required' => false, 'iconClass' => 'fa fa-user']) }}
										</div>
									</div>
									<div class="col-md-6 service-container">
										<div class="inner-field">{{ Form::jRadioSelect('hear_about', ['Google' => 'Google', 'Mailer' => 'Mailer', 'Referral' => 'Referral'], ['label' => 'How did you hear about us?', 'id' => 'how_did_you_hear_about_us', 'required' => false, 'iconClass' => 'icon-wrench-1']) }} </div>
										<div class="referring_person hidden">
											<div class="inner-field">
												{{ Form::jText('if_referral', ['label' => 'Referring Other Name', 'id' => 'referring_person', 'required' => false, 'iconClass' => 'fa fa-user']) }}
											</div>
										</div>
									</div>
								</div>
								<hr>
								 <h2 class="form-section-divider">Meeting/Assign Sales</h2>
								 <div class="row">
									<div class="col-md-6">
										
										<span class="caption-subject font-dark bold uppercase" style="clear: both;">Meeting <i class="field-required fa fa-asterisk" data-toggle="tooltip" title="" data-original-title="this field is required"></i></span>
											<div class="form-horizontal" role="form">
												<div class="form-body">											
													<div class="form-group">
														<label class="control-label col-md-3">Meeting Date</label>
														<div class="col-md-9">


															<div style="width:100%;" class="input-group date">
																<input value="<?php echo date('m/d/Y',time()); ?>" name="meeting_date" id="intake_date_meeting" class="date-meeting form-control" type="text" size="16">
															</div>


														</div>
													</div>


													<div class="form-group">
														<label class="control-label col-md-3">Meeting Time</label>
														<div class="col-md-9">
															<div class="input-icon">
																<i class="fa fa-clock-o"></i>
																<input name="meeting_time" id="intake_time_meeting" value="" type="text" class="time-meeting form-control timepicker timepicker-default">
															</div>
														</div>
													</div>                                   				
											   </div>
											</div>
									</div>
									<div class="col-md-6">
										<span class="caption-subject font-dark bold uppercase">Assign to</span>
										<div class="form-horizontal" role="form">
											<div class="form-body">

												<div class="form-group">
													<label class="col-md-3 control-label">Sales Manager</label>
													<div class="col-md-9">
														<select name="id_sales_manager" id="intake_sale_manager" class="form-control">
															<option value="" selected disabled>Select a Manager</option>
															@foreach($saleManager['saleManager'] as $item)
															<option value="{{$item['id']}}">{{$item['first_name'].' '.$item['last_name']}}</option>
															@endforeach
														</select>
													</div>
												</div>  

												<div class="form-group">
													<label class="col-md-3 control-label">Sales Associate</label>
													<div class="col-md-9">
														<select name="id_sales_person" class="form-control">
															<option value="" selected disabled>Select an Associate</option>
															@foreach($salePerson['salePerson'] as $item)
															<option value="{{$item['id']}}">{{$item['first_name'].' '.$item['last_name']}}</option>
															@endforeach
														</select>
													</div>
												</div>                                    
										   </div>
										</div>
									</div>
								</div>
								<hr>
								 <h2 class="form-section-divider">Acttachment</h2>
								<div class="row">
									<div class="col-md-6 col-xs-12">
										<input name="file_upload" type="file" />
										<p class="help-block">Attach blueprints or other files.</p>
									</div>
								</div>
								</div>
							
						</div>
						<div class="panel-footer text-right">
							<div class="row">
								<div class="col-sm-12">
									<button id="#cancel-button" type="button" class="btn dark btn-outline">Close</button>
									<input id="submit-intake" type="submit" class="btn green" value="Create Lead and Send Email"/>
								</div>
							</div>
                        </div>
                    </div>
					</form>
                </div>
            </div>
        </div>
    </section>

@stop

@section('css-page-level-plugins')
    {!! Html::style($publicUrl . '/assets/global/plugins/select2/css/select2.min.css') !!}
    {!! Html::style($publicUrl . '/assets/global/plugins/select2/css/select2-bootstrap.min.css') !!}
@stop

@section('js-page-level-scripts')
    {!! Html::script($publicUrl .'/assets/global/plugins/select2/js/select2.full.min.js') !!}
@stop
<style>
.select2-container .select2-selection{
	height:39px !important;
}
#ui-datepicker-div{
	z-index:999999 !important;
}
#description {
font-family: Roboto;
font-size: 15px;
font-weight: 300;
}
.pac-container.pac-logo.hdpi{
	z-index:9999999 !important;
}
#intake .select .field-icon {
    top: 12px;
    left: 0px;
    z-index: 40;
    width: 42px;
    height: 42px;
    color: inherit;
    line-height: 42px;
    position: absolute;
    text-align: center;
    transition: all 0.5s ease-out 0s;
    pointer-events: none;
}
#intake .field.select{
	position:relative;
	display:block !important;
	width:100%;
}
#intake .prepend-icon .field-icon {
    top: 12px;
    z-index: 4;
    width: 42px;
    height: 42px;
    color: inherit;
    line-height: 42px;
    position: absolute;
    text-align: center;
    -webkit-transition: all .5s ease-out;
    -moz-transition: all .5s ease-out;
    -ms-transition: all .5s ease-out;
    -o-transition: all .5s ease-out;
    transition: all .5s ease-out;
    pointer-events: none;
}
#intake .prepend-icon > input{
	padding-left:36px;
}
.service-container .form-group button.multiselect{
	width: 100%;
    position: relative;
    background-color: inherit;
    text-align: left;
}
.service-container .btn-group{
	width:100%;
	display:block !important;
}
.service-container .form-group{
	margin:0 !important;
}
.service-container .multiselect-container{
	width:100% !important;
	top:34px;
}
.hidden{
	display:none;
}
.select2-selection.select2-selection--single{
	height:39px !important;
}

.required{
	border:1px solid #f00 !important;
}
.form-control[readonly]{
	background-color:#fff !important;
}
.container-properties{
	position:relative;
}
#intake input[type="text"]{
	border-radius:4px;
	height:39px;
}
</style>
@section('js-files')
<script>
$(function(){
		var htmlPropertiesCB = {!! $myProperties['json_html_property'] !!};

		$('#new_intake_property_id').select2({
			data: htmlPropertiesCB,
			escapeMarkup: function(markup) {
				return markup;
			},
			templateResult: function(data) {
				return data.html;
			},
			templateSelection: function(data) {
				return data.text;
			}
		});

		$('#new_intake_property_id_add').select2({
			data: htmlPropertiesCB,
			escapeMarkup: function(markup) {
				return markup;
			},
			templateResult: function(data) {
				return data.html;
			},
			templateSelection: function(data) {
				return data.text;
			}
		});

		$('#intake_service_category_ids').multiselect({
			includeSelectAllOption: true
		});
		$('.if_other').hide();
		$('#intake_service_category_ids').change(function(){
			var array_service_intake = $(this).val();
			if(array_service_intake !== null)
			{
				for(i = 0;i<array_service_intake.length;i++)
				{
					if(array_service_intake[i] === '5')
					{
						$('.if_other').show();
					}
					else{
						$('.if_other').hide();
					}
				}
			}
			else
			{
				$('.if_other').hide();
			}
		});
		$('#how_did_you_hear_about_us').val('').multiselect();
		$('#how_did_you_hear_about_us').change(function(){
			if ($(this).val() == 'Referral') {
				$('.referring_person').removeClass('hidden');
			} else {
				$('.referring_person').addClass('hidden');
				$('#referring_person').val('');
			}
		});
		/*validate*/
		$('#intake').validate({
                rules: {
                    firstname: {
                        required : true,
                        plainText: false
                    },
                    lastname: {
                       required: true,
                       plainText: false
                    },
					phone: {
                        required: true,
                        plainText: false
                    },
					email: {
                        required: true,
                        plainText: false,
						email: true,
                    },
					meeting_date:{
						required: true,
					},
					meeting_time:{
						required: true,
					},
					new_property_id:{
						required:true,
					}
                },
                messages: {

                },
                submitHandler: function(form) {
                    showSpinner();
                    form.submit();
            }
        });
		/**/
		$('#new_intake_property_id_add').change(function(){
			var property = $('#new_intake_property_id_add').val();
			$.ajax({
				  url:"{{ route('ajax_get_information_property') }}",
				  data:{
					  id_properties:property,
				  },
				   beforeSend: function (request){
					PNotify.removeAll();
					showSpinner();
				  },
				  complete: function(){
					hideSpinner();
				  },
				  dataType:'json',
				  type:'post',
				  success:function(response){
					$('#intake_company_id').val(response.company_id);
					$('#intake_company_name').val(response.company);
					$('#intake_address_company').val(response.company_address);
					$('#intake_company_city').val(response.company_city);
					$('#intake_company_state').val(response.company_state);
					$('#intake_company_zipcode').val(response.company_zipcode);
					$('#intake_address_property').val(response.properties_address);
					$('#intake_city_property').val(response.properties_city);
					$('#intake_state_property').val(response.properties_state);
					$('#intake_zipcode_property').val(response.properties_zipcode);

					$('#intake_company_id').val(response.company_id);
					$('#intake_property_id').val(response.id_properties);
					},
				});
		});
		$('#cancel-button').click(function(ev){
				ev.preventDefault();
				window.location = "{{ route('dashboard') }}";
		});
			/**/
		$('input[name="file_upload"]').fileuploader();
	});
</script>
@stop
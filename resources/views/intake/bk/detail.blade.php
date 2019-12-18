@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>Intake Detail</span></li>
    </ul>
@stop

@section('content')
    <div class="page-content-inner">
        <div class="row">
            <div class="col-md-12">
                
                <div class="portlet box admin-form">
                    <div class="portlet-body">
                        <div class="clearfix">
							@include('errors._list')
							<div class="row">
								<form id="intake_form_detail" action="{{route('intake_update')}}" method="post">
									<div class="col-sm-7" style="padding: 20px;">
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
						
										<div class="portlet grey-cascade box">
											<div class="portlet-title">
												<div class="caption">
													Customer Details </div>

											</div>
											<div class="portlet-body">
												<div class="row">
													<div class="col-md-12 static-info">
														<span class="form-field-label">Date</span>
														<div style="width:100%;" class="input-group date form_meridian_datetime">
															<input value="{{ \Carbon\Carbon::parse($lead_detail[0]['date_intake'])->format('m/d/Y')}}" name="date_intake_detail" type="text" size="16" class="form-control intake-date">
															<span class="input-group-btn">
																	<button class="btn default date-set" type="button">
																		<i class="fa fa-calendar"></i>
																	</button>
																</span>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-6 static-info">
														<span class="form-field-label">First Name <span class="require">*</span></span>
														<input name="id_appointment" type="hidden" value="{{ $lead_detail[0]['id'] }}" class="form-control">
														<input name="lead_id_detail" type="hidden" value="{{ $lead_data->id }}" class="form-control">
														<input id="first_name_detail" name="first_name_detail" type="text" value="{{ $lead_data->first_name }}" class="form-control" placeholder="Enter FirstName">
													</div>
													<div class="col-md-6 static-info">
														<span class="form-field-label">Last Name <span class="require">*</span></span>
														<input id="last_name_detail" name="last_name_detail" type="text" value="{{ $lead_data->last_name }}" class="form-control" placeholder="Enter LastName">
													</div>
												</div>
												<div class="row">
													<div class="col-md-6 static-info">
														<span class="form-field-label">Phone Number <span class="require">*</span></span>
														<input id="phone_detail" name="phone_detail" type="tel" value="{{ $lead_data->phone }}" class="form-control" placeholder="Enter Phone">
													</div>
													<div class="col-md-6 static-info">
														<span class="form-field-label">Address<span class="require">*</span></span>
														<input id="email_detail" name="email_detail" type="email" value="{{ $lead_data->email }}" class="form-control" placeholder="Enter Email">
													</div>
												</div>							    

											</div>
										</div>

										<div class="portlet grey-cascade box">
											<div class="portlet-title">
												<div class="caption">
													Community / Company Details </div>
											</div>
											<div class="portlet-body">
												<div class="row">
													<div class="col-md-6 static-info">

														<div class="inner-field">{{ Form::jSelect2('community_detail', [], ['label' => 'Communities/ Property Name', 'selected' => null, 'required' => true, 'iconClass' => 'fa fa-building', 'attributes' => ['id' => 'new_intake_property_detail']]) }}</div>
														<span class="form-field-label">Address</span>
														<div class="inner-field"><input id="properties_address_detail" readonly value="{{$properties_data['properties_address']}}" name="street_address" type="text" class="form-control" placeholder="Enter Street Address"></div>

														<div class="inner-field"><input id="properties_city_detail" readonly value="{{$properties_data['properties_city']}}" name="city" type="text" class="form-control" placeholder="Enter City"></div>

														<div class="inner-field">
														
															<input id="properties_state_detail" readonly value="{{$properties_data['properties_state']}}" name="state" type="text" class="form-control" placeholder="Enter State">
															<input id="properties_state_id_detail" readonly value="{{$properties_data['properties_state_id']}}" name="state_id" type="hidden" class="form-control" placeholder="Enter State">
														</div>

														<div class="inner-field"><input id="properties_zipcode_detail" readonly value="{{$properties_data['properties_zipcode']}}" name="zipcode" type="text" class="form-control" placeholder="Enter Zip"></div>

													</div>
													<div class="col-md-6 static-info">
														<div class="inner-field">
															<span class="form-field-label">Company</span>
															<input id="company_name_detail" readonly type="text" value="{{$properties_data['company']}}" name="name_company" class="form-control" placeholder="Company Names">
														</div>
														<span class="form-field-label">Address</span>
														<div class="inner-field"><input id="company_address_detail" readonly value="{{$properties_data['company_address']}}" name="street_address_com" type="text" class="form-control" placeholder="Enter Street Address"></div>

														<div class="inner-field"><input id="company_city_detail" readonly value="{{$properties_data['company_city']}}"  name="city_com" type="text" class="form-control" placeholder="Enter City"></div>

														<div class="inner-field"><input id="company_state_detail" readonly value="{{$properties_data['company_state']}}" name="state_com" type="text" class="form-control" placeholder="Enter State"></div>

														<div class="inner-field"><input id="company_zipcode_detail" readonly value="{{$properties_data['company_zipcode']}}" name="zipcode_com" type="text" class="form-control" placeholder="Enter Zip"></div>

													</div>
												</div>
																			

											</div>
										</div>


										<div class="portlet grey-cascade box">
											<div class="portlet-title">
												<div class="caption">
													Question Details </div>
											</div>
											<div class="portlet-body">
												<div class="row">
													<div class="col-md-6 static-info">
														 <label class="control-label"> What Services were you looking for?: </label>
														@php
															$array_service = explode(',',$lead_detail[0]['id_service']);
														@endphp
														
														 <div class="mt-checkbox-list service-intake">
															@foreach ($service as $item)
																<label class="mt-checkbox mt-checkbox-outline checkbox-service-intake"> {{$item['name']}}
																	<input <?php if(in_array($item['id'],$array_service)) echo 'checked="checked"'; ?> type="checkbox" value="{{$item['id']}}" name="id_service_detail[]">
																	<span></span>
																</label>
															@endforeach

														</div>
														<div class="if_other">
																<label class="control-label">If Other</label>
																<textarea name="other_service_detail" rows="4" class="form-control" placeholder="Type special instructions"></textarea>
														</div>
														<div class="value">{{ $lead_detail[0]['other_service'] }}</div>
													</div>
													<div class="col-md-6 static-info">
														<label class="control-label"> How did you hear about us?: </label>
															@php 
																$checked_hear_about1 = '';
																$checked_hear_about2 = '';
																$checked_hear_about3 = '';
																$style = '';
																if($lead_detail[0]['hear_about'] == 'Google')
																	$checked_hear_about1 = 'checked="checked"';
																else if($lead_detail[0]['hear_about'] == 'Mailer')
																	$checked_hear_about2 = 'checked="checked"';
																elseif($lead_detail[0]['hear_about'] == 'Referral')
																{
																	$checked_hear_about3 = 'checked="checked"';
																	$style = 'display:block;';
																}
															@endphp
														<div class="mt-checkbox-list">
															<label class="mt-radio">
																<input {{$checked_hear_about1}}  type="radio" name="hear_about_detail" value="Google"> Google
																<span></span>
															</label>
															<label class="mt-radio">
																<input {{$checked_hear_about2}} type="radio" name="hear_about_detail" value="Mailer"> Mailer
																<span></span>
															</label>
															<label class="mt-radio">
																<input {{$checked_hear_about3}} type="radio" name="hear_about_detail" value="Referral"> Referral
																<span></span>
															</label>
															<div class="is_referral" style="{{$style}}">
																<input value="{{ $lead_detail[0]['hear_about_other'] }}" name="hear_about_other_detail" type="text" class="form-control" placeholder="Type person's name">
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										
										<div class="portlet grey-cascade box">
											<div class="portlet-title">
												<div class="caption">
													Email Customer </div>
											</div>
											<div class="portlet-body">
												<div class="row">
													<div class="col-md-12">
														
														<!--<form role="form">-->
															<div class="form-body">
																<div class="form-group">
																	<label class="control-label">Choose Email Template </label>
																	<select name="id_choose_template" class="form-control">
																		<option value="1" selected >Template 1</option>
																		<option value="2" selected >Template 2</option>
																	</select>
																</div>
																<div class="form-group">
																	To: <input type="text" class="form-control" name="email_to" value="{{$lead_data->email}}">
																</div>
																<div class="form-group">
																	Subject: <input type="text" class="form-control" name="subject" value="">
																</div>
																<div class="form-group">
																		<textarea id="content_email" placeholder="Write content" class="form-control" style="height: 200px;"></textarea>
																</div>
																											
														   </div>
															<div class="form-actions">
																<button id="send-email-customer" type="button" class="btn green">Preview Template</button>
															</div>	                               
														<!--</form>-->

													</div>
												</div>
											</div>
										</div>												
									   
									</div>

								<div class="col-sm-5" style="padding: 20px;">

									<div class="portlet-body form">

										
										<div class="form-horizontal" role="form">
											<div class="form-group">
												<div class="col-md-12">
													<button type="submit" class="btn green" style="float: right;">Save</button>
												</div>
											</div>
										</div>

										<hr>

										<span class="caption-subject font-dark bold uppercase">Assign to</span>
										<div class="form-horizontal" role="form">
											<div class="form-body">

												<div class="form-group">
													<label class="col-md-3 control-label">Sales Manager</label>
													<div class="col-md-9">
														<select name="id_sales_manager_detail" id="intake_sale_manager" class="form-control">
															<option value="" selected disabled>Select a Manager</option>
															
															@foreach($saleManager['saleManager'] as $item)
																<option <?php if($item['id'] == $lead_detail[0]['id_sales_manager']) echo 'selected="selected"'; ?> value="{{$item['id']}}">{{$item['first_name'].' '.$item['last_name']}}</option>
															@endforeach
														</select>
													</div>
												</div>

												<div class="form-group">
													<label class="col-md-3 control-label">Sales Associate</label>
													<div class="col-md-9">
														<select name="id_sales_person_detail" class="form-control">
															<option value="" selected disabled>Select a Associate</option>
															@foreach($salePerson['salePerson'] as $item)
															<option <?php if($item['id'] == $lead_detail[0]['id_sales_person']) echo 'selected="selected"'; ?>  value="{{$item['id']}}">{{$item['first_name'].' '.$item['last_name']}}</option>
															@endforeach
														</select>
													</div>
												</div>                                    

											   <div class="form-group">
													<label class="col-md-3 control-label"></label>
													<div class="col-md-9">
														<button type="submit" class="btn green">Save</button>
													</div>
												</div>

										   </div>
										</div>


										<span class="caption-subject font-dark bold uppercase" style="clear: both;">Meeting Details</span>
										<div class="form-horizontal" role="form">
											<div class="form-body">
														  
												<div class="form-group">
													<label class="control-label col-md-3">Meeting Date</label>
													<div class="col-md-9">


														<div style="width:100%;" class="input-group date">
															<input name="meeting_date_detail" id="meeting_date_detail" value="<?php echo date('m/d/Y',strtotime($lead_detail[0]['meeting_date'].' '.$lead_detail[0]['meeting_time'])); ?>" class="date-meeting form-control" type="text" size="16">
															<span class="input-group-btn">
																<button class="btn default date-set" type="button">
																	<i class="fa fa-calendar"></i>
																</button>
															</span>
														</div>


													</div>
												</div>


												<div class="form-group">
													<label class="control-label col-md-3">Meeting Time</label>
													<div class="col-md-9">
														<div class="input-icon">
															<i class="fa fa-clock-o"></i>
															<input name="meeting_time_detail" id="meeting_time_detail" value="<?php echo date('H:i',strtotime($lead_detail[0]['meeting_date'].' '.$lead_detail[0]['meeting_time'])); ?>" type="text" class="time-meeting form-control timepicker timepicker-default">
														</div>
													</div>
												</div>

											   <div class="form-group">
													<label class="col-md-3 control-label"></label>
													<div class="col-md-9">
														<button type="submit" class="btn green">Save</button>
													</div>
												</div>                                    
															
										   </div>
										</div>

										<div class="portlet light bordered">
											<div class="portlet-title"><div class="caption">Comments</div></div>
											<div class="portlet-body form">
												<!--<form role="form">-->
													<div class="form-body">

														<div class="form-group">
																<textarea id="content_comment" name="comment" class="form-control" style="height: 130px;" placeholder="Add comment here...">{{$lead_detail[0]['comment']}}</textarea>
														</div>
																									
												   </div>
													<div class="form-actions right">
														<button id="add_comment" type="button" class="btn green">Submit</button>
													</div>	                               
												<!--</form>-->
											</div>
										</div>



										<div class="portlet light bordered">
											<div class="portlet-title"><div class="caption">History</div></div>
											<div class="portlet-body form">
												<!--<form role="form">-->
													<div class="form-body">

														<div class="form-group">

																<style>
																	.form-control.text-area {
																		height: auto;
																		min-height: 180px;
																		background: #f5f5f5;
																	}
																</style>
																<div contenteditable="false" class="form-control text-area" rows="6">
																	Lead created: {{ \Carbon\Carbon::parse($lead_detail[0]['date_intake'])->format('m/d/Y')}}<br>
																	Assigned to {{$properties_data['sales_manager']}}<br>
																	Meeting Set Date: <?php echo date('m/d/Y',strtotime($lead_detail[0]['meeting_date'].' '.$lead_detail[0]['meeting_time'])); ?><br>
																	Meeting Set Time: <?php echo date('H:i',strtotime($lead_detail[0]['meeting_date'].' '.$lead_detail[0]['meeting_time'])); ?>
																</div>

														</div>
																									
												   </div>
										   
												<!--</form>-->
											</div>
										</div>

									</div>
								  

								</div>					
							</form>
							</div>
                        </div>

                        <div class="">
							
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<style>
		label{
			margin-bottom:5px !important;
		}
		.require{
			color:#f00;
		}
		.select2-container .select2-selection,.field.select{
			height:39px !important;
		}
		.select .field-icon {
			top: 0px;
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
		.field.select{
			position:relative;
			display:block !important;
			width:100%;
		}
		.prepend-icon .field-icon {
			top: 0;
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
		.prepend-icon > input{
			padding-left:36px;
		}
		#select2-new_intake_property_detail-container
		{
			line-height:normal !important;
		}
		.select2-container--default .select2-selection--single .select2-selection__arrow{
			height:35px !important;
		}
	</style>
@stop
@section('js-files')
	<script>
		$(document).ready(function(){
			var htmlPropertiesCB = {!! $myProperties['json_html_property'] !!};

            $('#new_intake_property_detail').select2({
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
			$('#new_intake_property_detail').val('{{$properties_data["id_properties"]}}').trigger("change");
		});
		/**/
		$('#new_intake_property_detail').change(function(){
				var property = $('#new_intake_property_detail').val();
				$.ajax({
					  url:"{{ route('ajax_get_information_property') }}",
					  data:{
						  id_properties:property,
					  },
					   beforeSend: function (request){
						PNotify.removeAll();
						//showSpinner();
					  },
					  complete: function(){
						//hideSpinner();
					  },
					  dataType:'json',
					  type:'post',
					  success:function(response){
							$('#company_name_detail').val(response.company);
							$('#company_address_detail').val(response.company_address);
							$('#company_city_detail').val(response.company_city);
							$('#company_state_detail').val(response.company_state);
							$('#company_name_zipcode').val(response.company_zipcode);
							$('#properties_address_detail').val(response.properties_address);
							$('#properties_city_detail').val(response.properties_city);
							$('#properties_state_detail').val(response.properties_state);
							$('#properties_zipcode_detail').val(response.properties_zipcode);
						},
					});
			});
	</script>
	<script>
			$('#send-email-customer').click(function(){
				var content = $('#content_email').val();
				if(content !== '')
				{
					$.ajax({
					  url:"{{ route('ajax_send_email_to_customer') }}",
					  data:{
						content: content,
						email:'{{$lead_data->email}}'
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
							if(response.status === true)
							{
								pnAlert({
                                type: 'success',
                                title: 'Success',
                                text: response.message,
                                addClass: 'mt50'
								});
							}
							else
							{
								pnAlert({
                                type: 'error',
                                title: 'Error',
                                text: response.message,
                                addClass: 'mt50'
								});
							}	
						},
					});
				}
			});
			$('#add_comment').click(function(){
				var comment = $('#content_comment').val();
				if(comment !== '')
				{
					$.ajax({
					  url:"{{ route('ajax_add_comment_to_customer') }}",
					  data:{
						comment: comment,
						id_appointment:'{{$lead_detail[0]["id"]}}'
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
							if(response.status === true)
							{
								pnAlert({
                                type: 'success',
                                title: 'Success',
                                text: response.message,
                                addClass: 'mt50'
								});
							}
							else
							{
								pnAlert({
                                type: 'error',
                                title: 'Error',
                                text: response.message,
                                addClass: 'mt50'
								});
							}	
						},
					});
				}
			})
	</script>
@stop
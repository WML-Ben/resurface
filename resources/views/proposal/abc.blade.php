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
    <section id="content" class="animated fadeIn list-items admin-form">
        <div class="row intake-form">
            <div class="col-md-9 center-block">
                @include('errors._list')
                <div class="admin-form theme-primary">
                    <div class="panel">
					<form id="intake form" class="form-horizontal" enctype="multipart/form-data">
						<div id="choose_properties" class="row">
							<div class="col-md-12">
								<div class="inner-field">
								<span class="top-right-field-link">
                                    <a style="color:#119b49;" href="{{route('property_create')}}">New</a>
                                 </span>
									{{ Form::jSelect2('new_property_id_temp', [], ['label' => 'Select a Property', 'selected' => null, 'required' => true, 'iconClass' => 'fa fa-building', 'attributes' => ['id' => 'new_intake_property_id_1']]) }}
								  
								</div>
							</div>
						</div>
					<div id="new-customer">
                        <div class="row">
							<div class="col-md-12">
                                <label class="control-label">Date</label>
                                <div style="width:100%;margin-bottom:5px;" class="input-group date form_meridian_datetime">
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="inner-field">
									{{ Form::jSelect2('new_property_id', [], ['label' => 'Communities/ Property Name', 'selected' => null, 'required' => true, 'iconClass' => 'fa fa-building', 'attributes' => ['id' => 'new_intake_property_id_add_1']]) }}
									<input id="intake_property_id" class="gui-input"  placeholder="" name="properties_id" type="hidden">
								</div>
       
									<div class="inner-field"><span class="form-field-label">Address</span><input readonly id="intake_address_property" name="address_properties" type="text" class="form-control" placeholder="Enter Address"></div>

									<div class="inner-field"><input readonly id="intake_city_property" name="city" type="text" class="form-control" placeholder="Enter City"></div>

									<div class="inner-field"><input readonly id="intake_state_property" name="state" type="text" class="form-control" placeholder="Enter State"></div>

									<div class="inner-field"><input readonly id="intake_zipcode_property" name="zipcode" type="text" class="form-control" placeholder="Enter Zip"></div>
								
								<!--<div class="pac-card" id="pac-card">
								</div>
								<div id="map"></div>
								<div id="infowindow-content">
								  <img src="" width="16" height="16" id="place-icon">
								  <span id="place-name"  class="title"></span><br>
								  <span id="place-address"></span>
								</div>-->
                            </div>

                            <div class="col-md-6">
								<div class="inner-field" style="margin-bottom:5px;">
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

                        <hr>

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

                        <div class="row">
                            <div class="col-md-12">

                                <label class="control-label">File Upload</label>
                                <input name="file_upload" type="file" id="exampleInputFile">
                                <p class="help-block">Attach blueprints or other files.</p>

                            </div>
                        </div>
						</div>
                    </form>
                    </div>
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

@section('js-files')
    	<script>
			$(function(){
				var htmlPropertiesCB = {!! $myProperties['json_html_property'] !!};

				$('#new_intake_property_id_1').select2({
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

				$('#new_intake_property_id_1').change(function(){
					var property = $('#new_intake_property_id').val();
					if(property !== '0')
					{
						$('.submit-intake').show();
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
								$('#choose_properties').hide();
								$('#new-customer').show();
							  },
							  dataType:'text',
							  type:'post',
							  async:true,
							  success:function(response){

							},
						});
					}	
				});

			});
		</script>
@stop
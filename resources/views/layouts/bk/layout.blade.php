<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport" />

    @if (env('APP_ENV') != 'production')
    <!-- To prevent most search engine web crawlers from indexing a page -->
        <meta name="robots" content="noindex">
    @endif

    <title>{!! isset($seo['pageTitlePrefix']) ? html_entity_decode($seo['pageTitlePrefix']) : html_entity_decode($defaultSEO['pageTitlePrefix']) !!}{!! isset($seo['pageTitle']) ? html_entity_decode($seo['pageTitle']) : html_entity_decode($defaultSEO['pageTitle']) !!}</title>

    <link rel="shortcut icon" href="{{ $publicUrl }}/images/{{ $config['favico'] }}">

    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    {!! Html::style($protocol .'fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all') !!}
    {!! Html::style($publicUrl .'/assets/global/plugins/font-awesome/css/font-awesome.min.css') !!}
    {!! Html::style($publicUrl .'/assets/global/plugins/simple-line-icons/simple-line-icons.min.css') !!}
    {!! Html::style($publicUrl .'/assets/global/plugins/bootstrap/css/bootstrap.min.css') !!}
	{!! Html::style($publicUrl .'/assets/global/plugins/select2/css/select2.min.css') !!}
	{!! Html::style($publicUrl .'/assets/global/plugins/select2/css/select2-bootstrap.min.css') !!}
	{!! Html::style($publicUrl .'/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') !!}
	{!! Html::style($publicUrl .'/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
    {!! Html::style($publicUrl .'/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') !!}
	{!! Html::style($publicUrl .'/assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') !!}
	
	{!! Html::style($publicUrl .'/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') !!}
    <!-- END GLOBAL MANDATORY STYLES -->

    @yield('css-page-level-plugins')

    <!-- BEGIN THEME GLOBAL STYLES -->
    {!! Html::style($publicUrl .'/assets/global/css/components.min.css') !!}
    {!! Html::style($publicUrl .'/assets/global/css/plugins.min.css') !!}
    <!-- END THEME GLOBAL STYLES -->

    <!-- BEGIN THEME LAYOUT STYLES -->
    {!! Html::style($publicUrl .'/assets/layouts/layout/css/layout.min.css') !!}
    {!! Html::style($publicUrl .'/assets/layouts/layout/css/themes/darkblue.min.css') !!}
    {!! Html::style($publicUrl .'/assets/layouts/layout3/css/themes/default.min.css') !!}
    {!! Html::style($publicUrl .'/assets/layouts/layout3/css/custom.min.css') !!}
    <!-- BEGIN THEME LAYOUT STYLES -->

    {{--
    <!-- Tom's CSS: -->
    {!! Html::style($publicUrl .'/css/tomcss.css') !!}
    --}}
	{!! Html::style($publicUrl .'/css/tomcss.css') !!}

    <!-- Icon Fonts CSS:
        font-awesome-4.5.0,
        icomoon,
        glyphicons,
        glyphicons-pro
    -->
    {!! Html::style($publicUrl .'/fonts/icon-fonts.min.css') !!}

    {!! Html::style($publicUrl . '/fonts/fontello.css') !!}

    <!-- Frontend Icon fonts CSS  -->
    {!! Html::style($publicUrl .'/fonts/fontello.css') !!}

    <!-- Vendor CSS:
        theme,
        adminpanels,
        admin-forms,
        adminmodal,
        xeditor,
        address,
        typeahead,
        magnific-popup,
        html5imageupload,
        summernote-modified,
        dockmodal,
        bootstrap datetimepicker (also for datepicker)
    -->

    {!! Html::style($publicUrl . '/backend/css/vendor.min.css') !!}

    @yield('css-files')

    <!-- My base CSS:
        ui.dialog,
        PNotify,
        important,
        validate
    -->
    {!! Html::style($publicUrl .'/css/common-base.min.css') !!}

<!-- Mike's CSS: -->
    {!! Html::style($publicUrl .'/css/mike.css') !!}

    <!-- My CSS: -->
    {!! Html::style($publicUrl .'/css/app-backend.css') !!}
    {!! Html::style($publicUrl .'/css/app.css') !!}
	{!! Html::style($publicUrl .'/css/thai.css') !!}
	    <style>
        .map-image {
            margin-top: 20px;
			width:100%;
        }

        .stat-panel {
            background: #fff;
            border-radius: 2px;
            display: table;
            margin-bottom: 22px;
            overflow: hidden;
            position: relative;
            table-layout: fixed!important;
            width: 100%;        
        }

        .stat-cell {
            display: table-cell!important;
            overflow: hidden;
            padding: 20px;
            position: relative;
        }

        .stat-cell, .stat-row {
            float: none!important;
        }

        h3 {
            margin: 0 0 20px 0;
        }

        .caption-subject {
            margin-bottom: 10px;
            display: block;
        }

        .portlet-body {
            margin-bottom: 30px;
        }

        .portlet-body:last-child {
            margin-bottom: 0;
        }

        .feeds li .col2 {
            width: 105px;
            margin-left: -105px;
        }       
            
        .scroller {
            overflow: scroll;
        }

        .inner-field {
            margin-bottom: 10px;
        }

        @media only screen and (max-width: 768px) {
            .col-sm-6,
            .col-sm-4 {
                width: 100%;
                display: block !important;
            }   
        }   

        @media (min-width: 768px) {
            .modal-dialog {
                width: 850px;
                margin: 30px auto;
            }
        }    
		.feeds li .col1>.cont>.cont-col1>.label{
			padding: .1em .65em .2em !important;
		}
		.total-boxes .dashboard-stat2{
			padding: 15px 15px 30px !important;
		}
		body {
			height:auto !important;
		}
    </style>
</head>
<body itemscope itemtype="http://schema.org/WebPage" class="page-container-bg-solid">
    <meta itemprop="name" content="{!! !empty($seo['pageTitle']) ? str_replace('"', '',  html_entity_decode($seo['pageTitle'])) : str_replace('"', '',  html_entity_decode($defaultSEO['pageTitle'])) !!}"/>
    <meta itemprop="description" content="{!! !empty($seo['description']) ? html_entity_decode(str_replace('"', '',  $seo['description'])) : str_replace('"', '',  html_entity_decode($defaultSEO['description'])) !!}"/>
    <meta itemprop="keywords" content="{!! !empty($seo['keywords']) ? str_replace('"', '',  html_entity_decode($seo['keywords'])) : str_replace('"', '',  html_entity_decode($defaultSEO['keywords'])) !!}"/>

    <!-- company micro data -->
    <div itemscope itemtype="http://schema.org/Organization">
        <meta itemprop="name" content="{{ html_entity_decode($config['company']) }}">
        <meta itemprop="url" content="{{ asset('/') }}">
        <meta itemprop="logo" content="{{ $publicUrl }}/images/{{ $config['logo_white'] }}">
    </div>

    <noscript>
        <div class="alert warning">
            <i class="fa fa-left-sides-circle"></i> You seem to have Javascript disabled. This website needs javascript in order to function properly!
        </div>
    </noscript>

    {{--
    <a data-toggle="modal" href="#intake" class="intake-icon"><span class="hover">New Intake</span> <span class="icon">Intake</span></a>
    <a data-toggle="modal" href="#task" class="task-icon"><span class="hover">New Task</span><span class="icon">Task</span></a>
    <a href="crm/showCRMList" class="proposal-icon"><span class="hover">New Proposal</span><span class="icon">Proposal</span></a>
    --}}

    @include('layouts._modal_intake')
    @include('layouts._modal_task')

    <div class="page-wrapper">
        @include('layouts._header')

        <div class="page-wrapper-row full-height">
            <div class="page-wrapper-middle">
                <div class="page-container">
                    <div class="page-content-wrapper">
                            <div id="main-wrapper">
								<div class="stat-row" style="padding:60px 30px;">
									@yield('breadcrumbs')

									@yield('content')
								</div>
                            </div>
                    </div>

                    <a href="javascript:;" class="page-quick-sidebar-toggler"><i class="icon-login"></i></a>
                    @include('layouts._side_bar')

                </div>
            </div>
        </div>

        @include('layouts._footer')
    </div>

    @include('layouts._nav_quick')
    <div class="quick-nav-overlay"></div>

    <!-- Vendor JS:
       jquery,
       jquery-ui,
       bootstrap,
       utility,
       main,
       bootstrap-editable,
       address,
       typeahead 1y2,
       moment,
       admin-form-elements,
       jquery.magnific-popup,
       html5imageupload,
       jquery.ui-interactions,
       summernote-modified,
       summernote-ext-fontstyle,
       dockmodal,
       jquery-ui-timepicker,
       bootstrap datetimepicker (also for datepicker with pickTime: false, in app.js)
   -->
    {!! Html::script($publicUrl .'/backend/js/vendor.min.js') !!}

    <!--[if lt IE 9]>
    {!! Html::script($publicUrl .'/assets/global/plugins/respond.min.js') !!}
    {!! Html::script($publicUrl .'/assets/global/plugins/excanvas.min.js') !!}
    {!! Html::script($publicUrl .'/assets/global/plugins/ie8.fix.min.js') !!}
    <![endif]-->

    <!-- BEGIN CORE PLUGINS -->
    {{--
    {!! Html::script($publicUrl .'/assets/global/plugins/jquery.min.js') !!}
    {!! Html::script($publicUrl .'/assets/global/plugins/bootstrap/js/bootstrap.min.js') !!}
    --}}

    {!! Html::script($publicUrl .'/assets/global/plugins/js.cookie.min.js') !!}
    {!! Html::script($publicUrl .'/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') !!}
    {!! Html::script($publicUrl .'/assets/global/plugins/jquery.blockui.min.js') !!}
    {!! Html::script($publicUrl .'/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') !!}
    <!-- END CORE PLUGINS -->

    {!! Html::script($publicUrl .'/assets/global/plugins/moment.min.js') !!}
	{!! Html::script($publicUrl .'/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') !!}
    {!! Html::script($publicUrl .'/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') !!}
    {!! Html::script($publicUrl .'/assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') !!}
	{!! Html::script($publicUrl .'/assets/global/plugins/morris/morris.min.js') !!}
    {!! Html::script($publicUrl .'/assets/global/plugins/morris/raphael-min.js') !!}
	{!! Html::script($publicUrl .'/assets/global/plugins/select2/js/select2.full.min.js') !!}

	<!-- BEGIN THEME GLOBAL Chart -->
		{!! Html::script($publicUrl .'/assets/global/plugins/amcharts/amcharts/amcharts.js') !!}
		{!! Html::script($publicUrl .'/assets/global/plugins/amcharts/amcharts/serial.js') !!}
		{!! Html::script($publicUrl .'/assets/global/plugins/amcharts/amcharts/pie.js') !!}
		{!! Html::script($publicUrl .'/assets/global/plugins/amcharts/amcharts/radar.js') !!}
		{!! Html::script($publicUrl .'/assets/global/plugins/amcharts/amcharts/themes/light.js') !!}
		{!! Html::script($publicUrl .'/assets/global/plugins/amcharts/amcharts/themes/patterns.js') !!}
		{!! Html::script($publicUrl .'/assets/global/plugins/amcharts/amcharts/themes/chalk.js') !!}
		{!! Html::script($publicUrl .'/assets/global/plugins/amcharts/ammap/ammap.js') !!}
		{!! Html::script($publicUrl .'/assets/global/plugins/amcharts/ammap/maps/js/worldLow.js') !!}
		{!! Html::script($publicUrl .'/assets/global/plugins/amcharts/amstockcharts/amstock.js') !!}
		{!! Html::script($publicUrl .'/assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js') !!}
        {!! Html::script($publicUrl .'/assets/global/plugins/jquery.sparkline.min.js') !!}
	<!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN THEME GLOBAL Chart -->
    {!! Html::script($publicUrl .'/assets/global/scripts/app.min.js') !!}
    <!-- END THEME GLOBAL SCRIPTS -->
		{!! Html::script($publicUrl .'/assets/pages/scripts/dashboard.min.js') !!}
    @yield('js-page-level-scripts')

    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    {!! Html::script($publicUrl .'/assets/layouts/layout3/scripts/layout.min.js') !!}
    {!! Html::script($publicUrl .'/assets/layouts/layout3/scripts/demo.min.js') !!}
    {!! Html::script($publicUrl .'/assets/layouts/global/scripts/quick-sidebar.min.js') !!}
    {!! Html::script($publicUrl .'/assets/layouts/global/scripts/quick-nav.min.js') !!}
    {{-- {!! Html::script($publicUrl .'/assets/pages/scripts/components-date-time-pickers.min.js') !!} --}}
    <!-- END THEME LAYOUT SCRIPTS -->

    <!-- page specific plugins -->
    @yield('js-plugin-files')

    <!-- global JS variables -->
    <script>
     var site_url = base_url = "{{ $siteUrl }}",
         public_url = "{{ $publicUrl }}",
         images_url = public_url + '/images',
         media_url = "{{ $mediaUrl }}",
         lang     = "{{ $lang }}",
         logo = "{{ $config['logo_white'] }}";

     var actionController = "{{ $action['actionController'] }}",
         actionFunction = "{{ $action['actionFunction'] }}",
         actionParameterId = "{{ $action['actionParameterId'] }}",
         actionPrefix = "{{ $action['actionPrefix'] }}",
         actionQueryString = "{{ $action['actionQueryString'] }}",
         routeName = "{{ $action['routeName'] }}",
         previousUrl = "{{ $action['previousUrl'] }}";
    </script>

    <!-- My base JS:
        ui.dialog,
        PNotify,
        validators_en,
        jquery.validate
    -->
    {!! Html::script($publicUrl .'/js/common-base.min.js') !!}

    <!-- My global JS: -->
    {!! Html::script($publicUrl .'/js/app-global.js') !!}

    <!-- My JS: -->
    {!! Html::script($publicUrl .'/js/app-backend.js') !!}
    {!! Html::script($publicUrl .'/js/app.js') !!}

    @if (!empty($config['idleTimeOut']) && ($iddleTimeOut = (integer)$config['idleTimeOut']))
        @include('widgets._idle_timeout', ['idleTimeOut' => 1000 * 60 * $iddleTimeOut])
    @endif

    <script>
         $(document).ready(function(){
             $.ajaxSetup({ headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"} });

             var actionController = "{{ $action['actionController'] }}",
                 actionFunction = "{{ $action['actionFunction'] }}",
                 actionParameterId = "{{ $action['actionParameterId'] }}";

             checkViewport();

             var waitForFinalEvent=function(){var b={};return function(c,d,a){a||(a="I am a banana!");b[a]&&clearTimeout(b[a]);b[a]=setTimeout(c,d)}}();
             var fullDateString = new Date();

             $(window).resize(function () {
                 waitForFinalEvent(function(){
                     checkViewport();
                 }, 100, fullDateString.getTime())
             });

             $('.search-container .reset-button').click(function(){
                 window.location = $(this).data('route');
             });

             @if (!empty($jsEvent))
                 var evArr = $.parseJSON('{!! $jsEvent !!}');

                 $(evArr.target).trigger(evArr.name);
             @endif

             @if (!empty($jsFunction))
                 @if (!empty($jsFunction['arguments']))
                     executeFunctionByName("{{ $jsFunction['name'] }}", window, "{{ $jsFunction['arguments'] }}");
                 @else
                     executeFunctionByName("{{ $jsFunction['name'] }}", window);
                 @endif
             @endif

             $('.logout-link').click(function(ev){
                 ev.preventDefault();
                 $('#logout-form').submit();
             });

             $('.not-yet-available, not-yet-implemented').click(function(ev){
                 ev.stopPropagation();
                 ev.preventDefault();

                 notify({
                     message: ($(this).data('message')) ? $(this).data('message') : 'This feature is stil under development.',
                     addClass: 'mt50'
                 });
                 return false;
             });

             $('#globalSearchForm').submit(function(){
                 var form = $('#globalSearchForm');

                 if (! validateJForm(form)) {
                     return false;
                 }
             });

             $('#globalSearchForm input').focusout(function(ev) {
                 ev.stopPropagation();
                 ev.preventDefault();

                 if (!$(ev.relatedTarget).hasClass('submit')) {
                     $(this).val('');
                     $(this).removeClass('input-error').attr('placeholder', $(this).data('validator-default-placeholder'));
                 }
                 $('#globalSearchForm').find('.state-error').removeClass('state-error');
             });

             /*$('.show-spinner').click(function(){
                 showSpinner();
                 window.location = $(this).data('route');
             });*/
         });

         function checkViewport()
         {
             $('body').removeClass('xs').removeClass('sm').removeClass('md').removeClass('lg').removeClass('not-xs');
             if( isBreakpoint('xs') ) {
                 $('body').addClass('xs');
                 $('.list-table .xs-hidden').hide();
             } else {
                 $('body').addClass('not-xs');
                 if( isBreakpoint('sm') ) {
                     $('body').addClass('sm');
                     $('.list-table .xs-hidden').show();
                 } else if( isBreakpoint('md') ) {
                     $('body').addClass('md');
                     $('.list-table .xs-hidden').show();
                 } else if( isBreakpoint('lg') ) {
                     $('body').addClass('lg');
                     $('.list-table .xs-hidden').show();
                 }
             }
         }
         function isBreakpoint(alias) {
             return $('.device-' + alias).is(':visible');
         }
        </script>
		<!--Thai-->

		<script type="text/javascript">
			$(".intake-date").datetimepicker({format: 'mm/dd/yyyy'});
			$(".date-meeting").datepicker();
			$(".time-meeting").timepicker({showMeridian: false});
		</script>
		<script>
		$(function(){
				$('.hide-modal-intake-form').click(function(){
					$('#intake').modal('hide');
					$('#new_intake_property_id').val('0').trigger('change');
				});

				$('#intake-modal').click(function(){
	
					$('#intake').modal({
                        backdrop: 'static',
                        keyboard: true, 
                        show: true
					}); 
					$('#choose_properties').show();
					$('.submit-intake').hide();
					$('#new-customer').hide();
				});
				$('#new-customer').hide();
				$('.submit-intake').hide();
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
				$('#new_intake_property_id').change(function(){
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
								$("#new_intake_property_id_add").val(response.id_properties).trigger("change");
								$('#intake_company_id').val(response.company_id);
								$('#intake_property_id').val(response.id_properties);
							},
						});
					}	
				});
				$('#new_intake_property_id_add').change(function(){
					var property = $('#new_intake_property_id_add').val();
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

				$('.submit-intake').click(function(){
						var firstname = $('#intake_firstname').val();
						var lastname = $('#intake_lastname').val();
						var phone = $('#intake_phone_number').val();
						var email = $('#intake_email').val();
						var flag = 1;
						var property = $('#new_intake_property_id_add').val();
						var date = $('#intake_date_meeting').val();
						var time = $('#intake_time_meeting').val();

						if(firstname === '')
						{
							$('#intake_firstname').addClass('required');
						}
						else
						{
							$('#intake_firstname').removeClass('required');
						}
						if(lastname === '')
						{
							$('#intake_lastname').addClass('required');
						}
						else
						{
							$('#intake_lastname').removeClass('required');
						}
						if(phone === '')
						{
							$('#intake_phone_number').addClass('required');
						}
						else
						{
							$('#intake_phone_number').removeClass('required');
						}
						if(email === '')
						{
							$('#intake_email').addClass('required');
						}
						else
						{
							$('#intake_email').removeClass('required');
						}
						if(property === '0')
						{
							$('#new_intake_property_id_add').parent('.select').find('.select2-selection--single').addClass('required');
						}
						else{
							$('#new_intake_property_id_add').parent('.select').find('.select2-selection--single').removeClass('required');
						}
						if(date === '')
						{
							$('#intake_date_meeting').parent('.date').addClass('required');
						}
						else
						{
							$('#intake_date_meeting').parent('.date').removeClass('required');
						}
						if(time === '')
						{
							$('#intake_time_meeting').addClass('required');
						}
						else
						{
							$('#intake_time_meeting').removeClass('required');
						}
						if(firstname !== '' && lastname !== '' && phone !== '' && email !== '' && property !== '0' && date !== '' && time !== '')
						{
							var data = new FormData($("#intake form")[0]);
							$.ajax({
							  url:"{{ route('intake_create') }}",
							  data:data,
							  dataType:'json',
							  type:'post',
							   beforeSend: function (request){
								PNotify.removeAll();
								showSpinner();
							  },
							  complete: function(){
								hideSpinner();
								$('#intake').modal('hide');
								$('#choose_properties').show();
								$('.submit-intake').hide();
								$('#new-customer').hide();
								$("#new_intake_property_id").val('0').trigger("change");
								$('#intake_firstname').val('');
								$('#intake_lastname').val('');
								$('#intake_email').val('');
								$('#intake_phone_number').val('');
								$('#intake_sale_manager').val('');
								$('#id_sales_person').val('');
								$('#exampleInputFile').val('');
								window.location.href="{{route('intake_list')}}";
							  },
							  processData: false,
							  contentType: false,
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

			});
		</script>
        @yield('js-files')

    <div class="device-xs visible-xs viewport-item" data-viewport="xs"></div>
    <div class="device-sm visible-sm viewport-item" data-viewport="sm"></div>
    <div class="device-md visible-md viewport-item" data-viewport="md"></div>
    <div class="device-lg visible-lg viewport-item" data-viewport="lg"></div>
</body>
</html>

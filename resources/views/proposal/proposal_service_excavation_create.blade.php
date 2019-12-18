@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><a href="{{ route('proposal_list') }}">Proposals</a><i class="fa fa-angle-right"></i></li>
            <li><a href="{{ route('proposal_details_services', ['id' => $proposal->id]) }}">Services</a><i class="fa fa-angle-right"></i></li>
            <li><span><strong>Add New</strong></span></li>
        </ul>
    </div>
@stop

@section('content')
    <section id="content" class="animated fadeIn list-items admin-form plr0">
        <div class="row">
            <div class="col-md-12" id="proposal_steps">
                <div id="main-wrapper p0">
                    <div id="content-wrapper">
                        <div class="stat-panel">
                            <div class="stat-row">
                                @include('proposal._tabs', ['current' => 'services'])

                                @include('errors._list')

                                @include('proposal._proposal_header')

                                {!!Form::open(['url' => route('proposal_details_service_store', ['proposal_id' => $proposal->id]), 'id' => 'serviceForm']) !!}
                                    {!! Form::hidden('service_id', $service->id) !!}
                                    {!! Form::hidden('service_category_id', $service->service_category_id) !!}

                                    <!-- from totals fields section -->

                                    {!! Form::hidden('cost', null, ['id' => 'hidden_cost']) !!}     <!-- customer price  -->
                                    {!! Form::hidden('break_even', null, ['id' => 'hidden_break_even']) !!}
                                    {!! Form::hidden('combined_cost', null, ['id' => 'hidden_combined_cost']) !!}
                                    {!! Form::hidden('overhead', null, ['id' => 'hidden_overhead']) !!}

                                    {!! Form::hidden('materials_total_cost', null, ['id' => 'hidden_materials_total_cost']) !!}
                                    {!! Form::hidden('vehicles_total_cost', null, ['id' => 'hidden_vehicles_total_cost']) !!}
                                    {!! Form::hidden('equipment_total_cost', null, ['id' => 'hidden_equipment_total_cost']) !!}
                                    {!! Form::hidden('labor_total_cost', null, ['id' => 'hidden_labor_total_cost']) !!}
                                    {!! Form::hidden('others_total_cost', null, ['id' => 'hidden_others_total_cost']) !!}
                                    {!! Form::hidden('sub_contractors_total_cost', null, ['id' => 'hidden_sub_contractors_total_cost']) !!}

                                    <!-- from materials calculated fields section -->

                                    {!! Form::hidden('tons', null, ['id' => 'hidden_tons']) !!}
                                    {!! Form::hidden('loads', null, ['id' => 'hidden_loads']) !!}

                                    <div class="portlet blue-hoki box">
                                        <div class="portlet-title">
                                            <div class="caption">New Service: {{ $service->name }}</div>
                                        </div>
                                        <div class="portlet-body">
                                            <div class="row">
                                                <div class="col-md-6 admin-form-item-widget">
                                                    {{ Form::jShow($service->category->name, ['label' => 'Category', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-bars']) }}
                                                </div>
                                                <div class="col-md-6 admin-form-item-widget">
                                                    {{ Form::jShow($service->name, ['label' => 'Service Type', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-gears']) }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 admin-form-item-widget">
                                                    {{ Form::jText('name', ['value' => $service->name, 'label' => 'Service Name', 'id' => 'name', 'required' => true, 'iconClass' => 'fa fa-bookmark']) }}
                                                </div>
                                            </div>

                                            <div class="section-divider mb20 mt20"><span>Required input fields</span></div>

                                            <div id="required_fields_section">
                                                <div class="row">
                                                    <div class="col-sm-2 col-xs-4 admin-form-item-widget">
                                                        {{ Form::jText('profit', ['label' => 'Profit', 'id' => 'profit', 'required' => true, 'iconClass' => 'fa fa-dollar']) }}
                                                    </div>
                                                    <div class="col-sm-2 col-xs-4 admin-form-item-widget">
                                                        {{ Form::jText('square_feet', ['label' => 'Square Feet', 'id' => 'square_feet', 'required' => true, 'iconClass' => 'fa fa-hashtag']) }}
                                                    </div>
                                                    <div class="col-sm-2 col-xs-4 admin-form-item-widget">
                                                        {{ Form::jText('depth_in_inches', ['label' => 'Depth in Inches', 'id' => 'depth_in_inches', 'required' => true, 'iconClass' => 'fa fa-hashtag']) }}
                                                    </div>
                                                    <div class="col-sm-2 col-xs-4 admin-form-item-widget">
                                                        {{ Form::jText('cost_per_day', ['label' => 'Our Cost', 'id' => 'cost_per_day', 'required' => true, 'iconClass' => 'fa fa-dollar']) }}
                                                    </div>
                                                    <div class="col-sm-2 col-xs-4 admin-form-item-widget">
                                                        {{ Form::jShow($service->tons ?? '&nbsp;', ['label' => 'Tons', 'id' => 'show_tons', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-hashtag']) }}
                                                    </div>
                                                    <div class="col-sm-2 col-xs-4 admin-form-item-widget">
                                                        {{ Form::jShow($service->loads ?? '&nbsp;', ['label' => 'Loads', 'id' => 'show_loads', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-hashtag']) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row pb20">
                                                <div class="col-sm-12 tc">
                                                    <a href="javascript:;" class="calculate btn btn-primary">Calculate</a>
                                                </div>
                                            </div>

                                            <div class="section-divider mb20 mt15"><span>Totals</span></div>

                                            <div class="row">
                                                <div class="col-md-3 col-sm-3 admin-form-item-widget">
                                                    {{ Form::jShow('&nbsp;', ['label' => 'Customer Price', 'id' => 'show_cost', 'iconClass' => 'fa fa-dollar', 'class' => 'icon-class-container']) }}
                                                </div>
                                                <div class="col-md-3 col-sm-3 admin-form-item-widget">
                                                    {{ Form::jShow('&nbsp;', ['label' => 'Breakeven', 'id' => 'show_break_even', 'iconClass' => 'fa fa-dollar', 'class' => 'icon-class-container']) }}
                                                </div>
                                                <div class="col-md-3 col-sm-3 admin-form-item-widget">
                                                    {{ Form::jShow('&nbsp;', ['label' => 'Combined Cost', 'id' => 'show_combined_cost', 'iconClass' => 'fa fa-dollar', 'class' => 'icon-class-container']) }}
                                                </div>
                                                <div id="total_overhead_container" class="col-md-3 col-sm-3 admin-form-item-widget">
                                                    {{ Form::jShow('&nbsp;', ['label' => 'Over Head', 'hint' => '(calculated at 30%)', 'id' => 'show_overhead', 'iconClass' => 'fa fa-dollar', 'class' => 'icon-class-container']) }}
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-2 col-sm-4 admin-form-item-widget">
                                                    {{ Form::jShow('&nbsp;', ['label' => 'Materials', 'id' => 'show_materials_total_cost', 'iconClass' => 'fa fa-dollar', 'class' => 'icon-class-container']) }}
                                                </div>
                                                <div class="col-md-2 col-sm-4 admin-form-item-widget">
                                                    {{ Form::jShow('&nbsp;', ['label' => 'Vehicles', 'id' => 'show_vehicles', 'iconClass' => 'fa fa-dollar', 'class' => 'icon-class-container']) }}
                                                </div>
                                                <div class="col-md-2 col-sm-4 admin-form-item-widget">
                                                    {{ Form::jShow('&nbsp;', ['label' => 'Equipment', 'id' => 'show_equipment', 'iconClass' => 'fa fa-dollar', 'class' => 'icon-class-container']) }}
                                                </div>
                                                <div class="col-md-2 col-sm-4 admin-form-item-widget">
                                                    {{ Form::jShow('&nbsp;', ['label' => 'Labor', 'id' => 'show_labor', 'iconClass' => 'fa fa-dollar', 'class' => 'icon-class-container']) }}
                                                </div>
                                                <div class="col-md-2 col-sm-4 admin-form-item-widget">
                                                    {{ Form::jShow('&nbsp;', ['label' => 'Others', 'id' => 'show_others', 'iconClass' => 'fa fa-dollar', 'class' => 'icon-class-container']) }}
                                                </div>
                                                <div class="col-md-2 col-sm-4 admin-form-item-widget">
                                                    {{ Form::jShow('&nbsp;', ['label' => 'Sub Contractors', 'id' => 'show_sub_contractors', 'iconClass' => 'fa fa-dollar', 'class' => 'icon-class-container']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @include('proposal._proposal_text_create', ['obj' => $service])

                                    @include('proposal._location', ['obj' => $proposal])

                                    @include('proposal._vehicles_create', ['obj' => $service->category->vehicleTypes])

                                    @include('proposal._equipments_create', ['obj' => $service->category->equipments])

                                    @include('proposal._labors_create', ['obj' => $labors])

                                    @include('proposal._other_cost_categories_create', ['obj' => $otherCostCategories])

                                    @include('proposal._sub_contractors_create', ['obj' => $subContractors])

                                    <div class="portlet blue-hoki box">
                                        <div class="portlet-body">
                                            <div class="row">
                                                <div class="col-sm-12 tr">
                                                    <a href="{{ route('proposal_details_services', ['id' => $proposal->id]) }}" class="btn btn-default mr10">Cancel</a>
                                                    <a id="form_submit_button" href="javascript:;" class="btn btn-primary">Add Service</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {!!Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('css-page-level-plugins')
    {!! Html::style($publicUrl . '/css/tomcss.css') !!}
@stop

@section('js-page-level-scripts')
    {!! Html::script($siteUrl . '/assets/tinymce-4.3.12/tinymce.min.js') !!}
@stop

@section('js-files')
    <script>
        $(function(){
            mceUploadFileInit("{{ route('upload_tinymce_image') }}", "{{ csrf_token() }}");
            mceInit('#proposal_text');

            $('.toggle_checkboxes').change(function(){
                var table = $(this).parents('table');
                var checkAll = $(this).is(':checked');
                table.find('tbody .td-checkbox input:checkbox').each(function(){
                    if (checkAll) {
                        $(this).prop('checked', 'checked');
                    } else {
                        $(this).removeAttr('checked');
                        var tr = $(this).parents('tr');
                        tr.find('.gui-input').val('');
                        tr.find('em.state-error').remove();
                        tr.find('.field').removeClass('state-error').removeClass('state-success');
                    }
                });
            });

            $('tbody .td-checkbox input:checkbox').change(function(){
                if (!$(this).is(':checked')) {
                    var tr = $(this).parents('tr');
                    tr.find('.gui-input').val('');
                    tr.find('em.state-error').remove();
                    tr.find('.field').removeClass('state-error').removeClass('state-success');

                    calculateAll();
                }
            });

            $('#serviceForm table .gui-input').change(function(){
                var tr = $(this).parents('tr');

                tr.find('.td-checkbox input:checkbox').prop('checked', true);

                if (isPositive($(this).val()) && tr.find('.td-checkbox input:checkbox').is(':checked')) {
                    calculateAll();
                }
            });

            $('#serviceForm').validate({
                rules: {
                    name: {
                        required : true,
                        plainText: true
                    },
                    // Required Field Section
                    profit: {
                        required: true,
                        currency: true
                    },
                    square_feet: {
                        required: true,
                        float   : true
                    },
                    depth_in_inches: {
                        required: true,
                        float   : true
                    },
                    cost_per_day: {
                        required: true,
                        currency: true
                    },
                    // Location fields
                    address: {
                        required : false,
                        plainText: true
                    },
                    address_2: {
                        required : false,
                        plainText: true
                    },
                    city: {
                        required : false,
                        plainText: true
                    },
                    zipcode: {
                        required : false,
                        plainText: true
                    },
                    country_id: {
                        required: false,
                        zeroOrPositive: true
                    },
                    state_id: {
                        required: false,
                        zeroOrPositive: true
                    },
                    parcel_number: {
                        required : false,
                        plainText: true
                    },

                    // Vehicle Types
                    'vehicle_type_quantity[]': {
                        required: function(element) {
                            return $('#'+element.id).parents('tr').find('.checkbox').is(':checked');
                        },
                        positive: true
                    },
                    'vehicle_type_days_needed[]': {
                        required: function(element) {
                            return $('#'+element.id).parents('tr').find('.checkbox').is(':checked');
                        },
                        positive: true
                    },
                    'vehicle_type_hours_per_day[]': {
                        required: function(element) {
                            return $('#'+element.id).parents('tr').find('.checkbox').is(':checked');
                        },
                        positive: true
                    },

                    // Equipment
                    'equipment_quantity[]': {
                        required: function(element) {
                            return $('#'+element.id).parents('tr').find('.checkbox').is(':checked');
                        },
                        positive: true
                    },
                    'equipment_days_needed[]': {
                        required: function(element) {
                            return $('#'+element.id).parents('tr').find('.checkbox').is(':checked');
                        },
                        positive: true
                    },
                    'equipment_hours_per_day[]': {
                        required: function(element) {
                            return $('#'+element.id).parents('tr').find('.checkbox').is(':checked') && element.hasAttribute('required');
                        },
                        positive: true
                    },

                    // Labor Rates
                    'labor_quantity[]': {
                        required: function(element) {
                            return $('#'+element.id).parents('tr').find('.checkbox').is(':checked');
                        },
                        positive: true
                    },
                    'labor_days_needed[]': {
                        required: function(element) {
                            return $('#'+element.id).parents('tr').find('.checkbox').is(':checked');
                        },
                        positive: true
                    },
                    'labor_hours_per_day[]': {
                        required: function(element) {
                            return $('#'+element.id).parents('tr').find('.checkbox').is(':checked');
                        },
                        positive: true
                    },

                    // Other Costs
                    'other_cost_category_description[]': {
                        required: function(element) {
                            return $('#'+element.id).parents('tr').find('.checkbox').is(':checked');
                        },
                        plainText: true
                    },
                    'other_cost_category_cost[]': {
                        required: function(element) {
                            return $('#'+element.id).parents('tr').find('.checkbox').is(':checked');
                        },
                        currency: true
                    },

                    // Sub Contractors
                    'sub_contractor_description[]': {
                        required: function(element) {
                            return $('#'+element.id).parents('tr').find('.checkbox').is(':checked');
                        },
                        plainText: true
                    },
                    'sub_contractor_over_head[]': {
                        required: function(element) {
                            return $('#'+element.id).parents('tr').find('.checkbox').is(':checked');
                        },
                        zeroOrPositive: true
                    },
                    'sub_contractor_quoted_cost[]': {
                        required: function(element) {
                            return $('#'+element.id).parents('tr').find('.checkbox').is(':checked');
                        },
                        currency: true
                    },
                    'sub_contractor_have_bid[]': {
                        required: function(element) {
                            return $('#'+element.id).parents('tr').find('.checkbox').is(':checked');
                        },
                        boolean: true
                    }
                },
                messages: {
                    locations: {
                        max: 'Over head cannot be higher than {0}.'
                    },
                    vendor_id: {
                        positive: 'Please, select a sub contractor.'
                    }
                }
            });

            $('#reset_proposal_text_button').click(function(){
                var t = tinymce.get('proposal_text').setContent($('#show_proposal_text').html());
            });

            $('#form_submit_button').click(function(){
                var allOK = true;

                allOK = validateMceBeforeSubmit($('#proposal_text')) && allOK;
                allOK = validateSection($('#required_fields_section')) && allOK;
                allOK = validateSection($('#service_location_section')) && allOK;
                allOK = validateSelectedRowsMultiSection($('.multi-section')) && allOK;

                if (! allOK){
                    notify({
                        type: 'error',
                        title: 'Validation error',
                        message: 'Please, check invalid fields.'
                    });

                    return false;
                }

                calculateAll();

                removeUncheckedRows($('.multi-section'));

                $('#serviceForm').submit();
            });

            $('.calculate').click(function(){
                if (! validateSection($('#required_fields_section'))){
                    notify({
                        type: 'error',
                        title: 'Validation error',
                        message: 'Please, check invalid required fields and run calculation again.'
                    });
                    return false;
                }
                calculateAll();
            });

            $('#cancel-button').click(function(ev){
                ev.preventDefault();
                window.location = "{{ route('proposal_list') }}";
            });

            $('.checkbox-have-bid').change(function(){
                $(this).parents('td').find('.sub-contractor-have-bid').val($(this).is(':checked') | 0);
            });

            $('#country_id').change(function(ev){
                $.ajax({
                    type:"post",
                    url: "{{ route('ajax_state_fetch') }}",
                    data: {
                        country_id: $(this).val()
                    },
                    beforeSend: function (request){
                        PNotify.removeAll();
                        showSpinner();
                    },
                    complete: function(){
                        hideSpinner();
                    },
                    success: function(response){
                        $('#state_id').empty();
                        if (response.success) {
                            var html = Object.keys(response.data).length > 0 ? ['<option value="0">Select state</option>'] : [];
                            $.each(response.data, function(index, value){
                                html.push('<option value="'+ index +'">'+ value +'</option>')
                            });
                            $('#state_id').html(html.join(''));
                        } else {
                            pnAlert({
                                type: 'error',
                                title: 'Error',
                                text: response.message,
                                addClass: 'mt50'
                            });
                        }
                    }
                });
            });

        });

        function calculateVehicleSectionTotalCost()
        {
            var total = 0;

            $('#vehicle_types_section').find('tr').each(function(){
                if ($(this).find('.td-checkbox input:checkbox').is(':checked')) {
                    total += normalizeFloat($(this).data('rate')) * normalizeFloat($(this).find('.quantity').val()) * normalizeFloat($(this).find('.days-needed').val()) * normalizeFloat($(this).find('.hours-per-day').val());
                }
            });

            $('#section_vehicle_type_total_cost').html(formatMoney(total));

            $('#show_vehicles').html(formatMoney(total));
            $('#hidden_vehicles_total_cost').val(total);
        }

        function calculateEquipmentSectionTotalCost()
        {
            var total = 0;

            $('#equipment_section').find('tr').each(function(){
                if ($(this).find('.td-checkbox input:checkbox').is(':checked')) {
                    var subtotal = normalizeFloat($(this).data('cost')) * normalizeFloat($(this).find('.quantity').val()) * normalizeFloat($(this).find('.days-needed').val());

                    if ($(this).data('rate_type_id') == '1') {  // per hour
                        subtotal *= normalizeFloat($(this).find('.hours-per-day').val());
                    }
                    if (subtotal > 0 && normalizeFloat($(this).data('min_cost')) > subtotal) {
                        subtotal = normalizeFloat($(this).data('min_cost'));
                    }
                    total += subtotal;
                }
            });

            $('#section_equipment_total_cost').html(formatMoney(total));

            $('#show_equipment').html(formatMoney(total));
            $('#hidden_equipment_total_cost').val(total);
        }

        function calculateLaborSectionTotalCost()
        {
            var total = 0;

            $('#labor_rates_section').find('tr').each(function(){
                if ($(this).find('.td-checkbox input:checkbox').is(':checked')) {
                    total += normalizeFloat($(this).data('rate')) * normalizeFloat($(this).find('.quantity').val()) * normalizeFloat($(this).find('.days-needed').val()) * normalizeFloat($(this).find('.hours-per-day').val());
                }
            });

            $('#section_labor_total_cost').html(formatMoney(total));

            $('#show_labor').html(formatMoney(total));
            $('#hidden_labor_total_cost').val(total);
        }

        function calculateOtherCostsSectionTotalCost()
        {
            var total = 0;  // other_costs_section    section_other_cost_category_total_cost

            $('#other_costs_section').find('tr').each(function(){
                if ($(this).find('.td-checkbox input:checkbox').is(':checked')) {
                    total += normalizeFloat($(this).find('.cost').val());
                }
            });

            $('#section_other_cost_category_total_cost').html(formatMoney(total));

            $('#show_others').html(formatMoney(total));
            $('#hidden_others_total_cost').val(total);
        }

        function calculateSubContractorsSectionTotalCost()
        {
            var total = 0;

            $('#sub_contractors_section').find('tr').each(function(){
                if ($(this).find('.td-checkbox input:checkbox').is(':checked')) {
                    total += (100 - normalizeFloat($(this).find('.over_head').val())) / 100 * normalizeFloat($(this).find('.quoted_cost').val());
                }
            });

            $('#section_sub_contractor_total_cost').html(formatMoney(total));

            $('#show_sub_contractors').html(formatMoney(total));
            $('#hidden_sub_contractors_total_cost').val(total);
        }

        function calculateAll()
        {
            // set hidden fields on general from this form fields:     square_feet depth_in_inches

            var profit = normalizeFloat($('#profit').val());
            var squareFeet = normalizeFloat($('#square_feet').val());
            var depthInInches = normalizeFloat($('#depth_in_inches').val());
            var ourCost = normalizeFloat($('#cost_per_day').val());

            // do calculation on first block:

            var tons = squareFeet * depthInInches * 7 / 1080;
            var loads = Math.ceil(tons / 18);

            $('#hidden_tons').val(tons);
            $('#hidden_loads').val(loads);

            $('#show_tons').html(formatMoney(tons));
            $('#show_loads').html(formatMoney(loads));

            var materialsTotalCost = ourCost;

            $('#show_materials_total_cost').html('$' + formatMoney(materialsTotalCost));
            $('#hidden_materials_total_cost').val(materialsTotalCost);

            // vehicles

            calculateVehicleSectionTotalCost();

            var vehiclesTotalCost = normalizeFloat($('#hidden_vehicles_total_cost').val());


            // equipments

            calculateEquipmentSectionTotalCost();

            var equipmentTotalCost = normalizeFloat($('#hidden_equipment_total_cost').val());


            // labor

            calculateLaborSectionTotalCost();

            var laborTotalCost = normalizeFloat($('#hidden_labor_total_cost').val());


            // other

            calculateOtherCostsSectionTotalCost();

            var otherTotalCost = normalizeFloat($('#hidden_others_total_cost').val());

            // sub contrators

            calculateSubContractorsSectionTotalCost();

            var subContractorTotalCost = normalizeFloat($('#hidden_sub_contractor_total_cost').val());

            var combinedCost = materialsTotalCost + vehiclesTotalCost + equipmentTotalCost + laborTotalCost + otherTotalCost;

            $('#show_combined_cost').html('$' + formatMoney(combinedCost));
            $('#hidden_combined_cost').val(combinedCost);

            var otCost = (combinedCost + profit);
            var overhead = (otCost / 0.7) - otCost;

            $('#show_overhead').html(formatMoney(overhead));
            $('#hidden_overhead').val(overhead);

            var subCost = combinedCost + profit + overhead;

            if (typeof unit != 'undefined' && unit > 0) {
                var costPerUnit = subCost / unit;
                $('#show_unit_cost').html(formatMoney(costPerUnit));
                $('#hidden_unit_cost').val(costPerUnit);
            }

            // totals

            var breakEven = overhead + combinedCost;
            $('#show_break_even').html(formatMoney(breakEven));
            $('#hidden_break_even').val(breakEven);

            var totalCost = subCost + subContractorTotalCost;
            $('#show_cost').html(formatMoney(totalCost));
            $('#hidden_cost').val(totalCost);
        }

        function validateSelectedRowsMultiSection(section)
        {
            var valid = true;
            var form = section.parents('form');

            section.find('.gui-input').each(function (index, elem) {
                if ($(this).parents('tr').find('.checkbox').is(':checked')) {
                    var isElemValid = form.validate().element(elem);
                    if (isElemValid != null) {
                        valid = valid & isElemValid;
                    }
                }
            });

            return valid;
        }

        function validateSection(section)
        {
            var valid = true;
            var form = section.parents('form');

            section.find('.gui-input').each(function (index, elem) {
                var isElemValid = form.validate().element(elem);
                if (isElemValid != null) {
                    valid = valid & isElemValid;
                }
            });

            return valid;
        }

        function removeUncheckedRows(section)
        {
            section.find('.checkbox').each(function(){
                if (! $(this).is(':checked')) {
                    $(this).parents('tr').remove();
                }
            });
        }
    </script>
@stop
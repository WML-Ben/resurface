@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><a href="{{ route('proposal_list') }}">Proposals</a><i class="fa fa-angle-right"></i></li>
            <li><a href="{{ route('proposal_details_services', ['id' => $proposal->id]) }}">Services</a><i class="fa fa-angle-right"></i></li>
            <li><span><strong>Edit</strong></span></li>
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

                                {!!Form::model($orderService, ['url' => route('proposal_details_service_update', ['id' => $orderService->id]), 'method' => 'PATCH', 'id' => 'serviceForm']) !!}
                                    {!! Form::hidden('id', $orderService->id) !!}
                                    {!! Form::hidden('service_id', $service->id) !!}
                                    {!! Form::hidden('service_category_id', $service->service_category_id) !!}
                                    {!! Form::hidden('striping_vendor_id', $stripingVendor->id) !!}

                                    <!-- from totals fields section -->

                                    {!! Form::hidden('cost', null, ['id' => 'hidden_cost']) !!}     <!-- customer price  -->
                                    {!! Form::hidden('break_even', null, ['id' => 'hidden_break_even']) !!}
                                    {!! Form::hidden('striping', null, ['id' => 'hidden_striping']) !!}
                                    {!! Form::hidden('overhead', null, ['id' => 'hidden_overhead']) !!}

                                    {!! Form::hidden('others_total_cost', null, ['id' => 'hidden_others_total_cost']) !!}
                                    {!! Form::hidden('striping_services_total_cost', null, ['id' => 'hidden_striping_services_total_cost']) !!}

                                    <div class="portlet blue-hoki box">
                                        <div class="portlet-title">
                                            <div class="caption">Edit Service: {{ $service->name }}</div>
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
                                                <div class="col-sm-6 admin-form-item-widget">
                                                    {{ Form::jShow($stripingVendor->name, ['label' => 'Striping Vendor', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-user']) }}
                                                </div>
                                                <div class="col-sm-6 admin-form-item-widget">
                                                    {{ Form::jText('name', ['value' => $service->name, 'label' => 'Service Name', 'id' => 'name', 'required' => true, 'iconClass' => 'fa fa-bookmark']) }}
                                                </div>
                                            </div>

                                            <div class="section-divider mb20 mt20"><span>Required input fields</span></div>

                                            <div id="required_fields_section">
                                                <div class="row">
                                                    <div class="col-sm-3 col-xs-4 admin-form-item-widget">
                                                        {{ Form::jText('profit', ['label' => 'Profit', 'id' => 'profit', 'required' => true, 'iconClass' => 'fa fa-dollar']) }}
                                                    </div>
                                                    <div class="col-sm-9 col-xs-8 admin-form-item-widget tl">
                                                        <a href="javascript:;" class="calculate btn btn-primary not-xs-mt21">Calculate</a>
                                                    </div>
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
                                                    {{ Form::jShow('&nbsp;', ['label' => 'Striping', 'id' => 'show_striping', 'iconClass' => 'fa fa-dollar', 'class' => 'icon-class-container']) }}
                                                </div>
                                                <div id="total_overhead_container" class="col-md-3 col-sm-3 admin-form-item-widget">
                                                    {{ Form::jShow('&nbsp;', ['label' => 'Over Head', 'id' => 'show_overhead', 'iconClass' => 'fa fa-dollar', 'class' => 'icon-class-container']) }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @include('proposal._proposal_text_edit', ['obj' => $orderService])

                                    @include('proposal._location', ['obj' => $proposal])

                                    @include('proposal._striping_vendor_services_edit', ['obj' => $stripingServiceCategories, 'orderService' => $orderService])

                                    @include('proposal._other_cost_categories_edit', ['obj' => $otherCostCategories, 'orderService' => $orderService])

                                    <div class="portlet blue-hoki box">
                                        <div class="portlet-body">
                                            <div class="row">
                                                <div class="col-sm-12 tr">
                                                    <a href="{{ route('proposal_details_services', ['id' => $proposal->id]) }}" class="btn btn-default mr10">Cancel</a>
                                                    <a id="form_submit_button" href="javascript:;" class="btn btn-primary">Update Service</a>
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

                    // Striping Services
                    'striping_service_quantity[]': {
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
                    }
                }
            });

            $('#reset_proposal_text_button').click(function(){
                var t = tinymce.get('proposal_text').setContent($('#show_proposal_text').html());
            });

            $('#form_submit_button').click(function(){
                if ($('#hidden_striping').val() == '') {
                    notify({
                        type: 'error',
                        title: 'Error',
                        message: 'You must select at least one striping service.'
                    });
                    return false;
                }

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

            calculateAll();
        });

        function calculateStripingServicesSectionTotalCost()
        {
            var total = 0;

            $('#striping_services_section').find('tr').each(function(){
                if ($(this).find('.td-checkbox input:checkbox').is(':checked')) {
                    total += normalizeFloat($(this).data('price')) * normalizeFloat($(this).find('.quantity').val());
                }
            });

            $('#section_striping_service_total_cost').html(formatMoney(total));

            $('#show_striping').html(formatMoney(total));
            $('#hidden_striping').val(total);
        }

        
        function calculateOtherCostsSectionTotalCost()
        {
            var total = 0;

            $('#other_costs_section').find('tr').each(function(){
                if ($(this).find('.td-checkbox input:checkbox').is(':checked')) {
                    total += normalizeFloat($(this).find('.cost').val());
                }
            });

            $('#section_other_cost_category_total_cost').html(formatMoney(total));

            $('#show_others').html(formatMoney(total));
            $('#hidden_others_total_cost').val(total);
        }

        function calculateAll()
        {
            // set hidden fields on general from this form fields:     square_feet depth_in_inches

            var profit = normalizeFloat($('#profit').val());

            // do calculation on first block:


            // striping services

            calculateStripingServicesSectionTotalCost();

            var stripingServicesTotalCost = normalizeFloat($('#hidden_striping').val());

            // other

            calculateOtherCostsSectionTotalCost();

            var otherTotalCost = normalizeFloat($('#hidden_others_total_cost').val());

            var combinedCost = stripingServicesTotalCost + otherTotalCost;

            $('#show_striping').html('$' + formatMoney(combinedCost));
            $('#hidden_striping').val(combinedCost);

            var otCost = (combinedCost + profit);
            var overhead = (otCost / 0.7) - otCost;

            $('#show_overhead').html(formatMoney(overhead));
            $('#hidden_overhead').val(overhead);

            // totals

            var breakEven = overhead + combinedCost;
            $('#show_break_even').html(formatMoney(breakEven));
            $('#hidden_break_even').val(breakEven);

            var totalCost = overhead + combinedCost + profit;
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
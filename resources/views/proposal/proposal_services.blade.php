@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><a href="{{ route('proposal_list') }}">Proposals</a><i class="fa fa-angle-right"></i></li>
            <li><span>Details: <strong>Services</strong></span></li>
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
                                @include('proposal._tabs', ['current' => 'services', 'no_link' => true])

                                @include('errors._list')

                                <div class="portlet mt-element-ribbon royal-blue box">
                                    <div class="proposal-status-ribbon ribbon-color-success uppercase">Status: {{ $proposal->status->name }}</div>

                                    <div class="portlet-title">
                                        <div id="porlet_caption_proposal_name" class="caption">{{ $proposal->name }}</div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="row">
                                            <div class="col-md-12 static-info">
                                                <div class="name">Created for:</div>
                                                <div id="porlet_caption_company_name" class="value">{{ $proposal->company->name ?? '' }}</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-7 static-info">
                                                <div class="name">Property Name:</div>
                                                <div id="porlet_caption_property_name" class="value">{{ $proposal->property->name ?? '' }}</div>
                                            </div>
                                            <div class="col-md-5 static-info">
                                                <div class="name">Age:</div>
                                                <div class="value">{{ !empty($proposal->created_at) ? $proposal->created_at->diffInDays(now()) . ' ' . str_plural('day', $proposal->created_at->diffInDays(now())) : '' }}</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-7 static-info">
                                                <div class="name">Created Date:</div>
                                                <div class="value">{{ !empty($proposal->created_at) ? $proposal->created_at->format('l, F d, Y') : '' }}</div>
                                            </div>
                                            <div class="col-md-5 static-info">
                                                <div class="name">Last Update Date:</div>
                                                <div class="value">{{ !empty($proposal->updated_at) ? $proposal->updated_at->format('l, F d, Y') : '' }}</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-7 static-info">
                                                <div class="name">Created By:</div>
                                                <div class="value">{{ $proposal->createdBy->fullName ?? '' }}</div>
                                            </div>
                                            <div class="col-md-5 static-info">
                                                <div class="name">Last Update By:</div>
                                                <div class="value">{{ $proposal->updatedBy->fullName ?? '' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="portlet blue-hoki box">
                                    <div class="portlet-title">
                                        <div class="caption">Services</div>
                                    </div>
                                    <div class="portlet-body">
                                        <div class="row plr10">
                                            <div class="clearfix">
                                                <div class="col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15">
                                                    <div class="btn-group">
                                                        @if (auth()->user()->isAllowTo('create-order-service'))
                                                            <button id="button-create" class="btn btn-success mr10" type="button">
                                                                <i class="fa fa-plus mr10"></i>Add New
                                                            </button>
                                                        @endif
                                                        @if (auth()->user()->isAllowTo('update-proposal'))
                                                            {!! Form::open(['url' => route('proposal_details_services_reorder', ['id' => $proposal->id]), 'id' => 'reorderForm', 'class' => 'inline-form']) !!}
                                                                {!! Form::hidden('strCid', null, ['id' => 'reorderStrCid']) !!}
                                                                <button id="button-update-order" class="btn btn-info mr10">
                                                                    <i class="fa fa-sort-numeric-asc mr10"></i>Update Order
                                                                </button>
                                                            {!! Form::close() !!}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 static-info table-scrollable plr0 mt0">
                                                <table class="table table-bordered list-table sortable-table">
                                                    <thead>
                                                    <tr>
                                                        <th class="order">Order</th>
                                                        <th class="">Name</th>
                                                        <th class="xs-hidden tc">Category</th>
                                                        <th class="xs-hidden tc">Cost</th>
                                                        <th class="actions">Actions</th>
                                                    </tr>
                                                    </thead>

                                                    <tbody id="sortable-body">
                                                        @if ($proposal->services->count())
                                                            @foreach ($proposal->services as $service)
                                                                <tr data-id="{{ $service->id }}" class="">
                                                                    <td class="centered order">{{ $service->html_d_sort }}</td>
                                                                    <td>{{ $service->name }}{!! $service->service->default_rate > $service->cost ? '<div class="fs11 danger-color">Service Estimate does not meet the minimum cost</div>' : '' !!}</td>
                                                                    <td class="xs-hidden tc">{{ $service->category->name }}</td>
                                                                    <td class="xs-hidden tc">{{ $service->cost_currency }}</td>
                                                                    <td class="centered actions all-time-visible-actions">
                                                                        @if (auth()->user()->hasPrivilege('update-order-service'))
                                                                            <a href="{{ route('proposal_details_service_edit', ['proposal_id' => $proposal->id, 'service_id' => $service->id]) }}" class="action">Edit</a>
                                                                        @endif
                                                                        @if (auth()->user()->hasPrivilege('delete-order-service'))
                                                                            <a href="javascript:void(0);" class="action" data-text="Are you sure you want to remove this service?" data-action="delete" data-id="{{ $service->id }}">Remove</a>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 tr ptb10">
                                                <p>Total: <span class="dib w100 ">{{ $proposal->services_total_cost_currency }}</span></p>
                                                <p>Discount: <span class="dib w100">
                                                    <span data-toggle="tooltip" title="Click to edit">
                                                        <a class="x-editable" href="#"
                                                           data-pk="{{ $proposal->id }}"
                                                           data-name="discount"
                                                           data-value="{{ $proposal->discount }}"
                                                           data-js-validation-function="isZeroOrPositive"
                                                           data-js-validation-error-message="Invalid entry."
                                                           data-php-validation-rule="required|zeroOrPositive"
                                                           data-type="text"
                                                           data-title="Discount:"
                                                           data-url="{{ route('proposal_inline_update') }}">
                                                            {{ $proposal->discount }}
                                                        </a>
                                                    </span>%</span></p>
                                                <p>Grand Total: <span id="grand_total" class="dib w100 fwb pt5">{{ $proposal->services_total_cost_with_discount_currency }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {!! Form::jDeleteForm(route('proposal_details_service_delete')) !!}

    <div id="modal_new_service" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content jform-container">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                    <h4 class="modal-title">Select Service</h4>
                </div>
                {!!Form::open(['url' => route('proposal_details_service_create', ['proposal_id' => $proposal->id]), 'id' => 'createNewServiceForm', 'class' => 'jform-form']) !!}
                    <div class="modal-body jform-body">
                        <div class="alert alert-error jform-errors-container hidden">
                            <span class="jform-errors-content"></span>
                            <span class="close"></span>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group form-row validation-field-container">
                                    <label class="field-label text-muted">Service:</label>
                                    <label for="service_id" class="field select">
                                        <select
                                            name="service_id"
                                            id="service_id"
                                            class="validation-field formcontrol"
                                            data-validator-required="true"
                                            data-validator-function="isPositive"
                                            data-validator-message-required="This field is required."
                                            data-validator-message-error="Select service.">
                                            @foreach ($serviceCategoriesCB as $key => $value)
                                                <option value="{{ $key }}"{{ !empty($service_id) && $key == $service_id ? ' selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        <span class="field-icon"><i class="fa fa-gears"></i></span>
                                        <i class="arrow double"></i>
                                    </label>
                                    <span class="error-message"></span>
                                </div>
                            </div>
                        </div>
                        <div id="striping_vendor_container" class="row hidden">
                            <div class="col-sm-12">
                                <div class="form-group form-row validation-field-container">
                                    <label class="field-label text-muted">Striping Vendor:</label>
                                    <label for="striping_vendor_id" class="field select">
                                        <select
                                            name="striping_vendor_id"
                                            id="striping_vendor_id"
                                            class="validation-field formcontrol"
                                            data-validator-required="true"
                                            data-validator-function="isPositive"
                                            data-validator-message-required="This field is required."
                                            data-validator-message-error="Select Vendor.">
                                            @foreach ($stripingVendorsCB as $key => $value)
                                                <option value="{{ $key }}"{{ !empty($service_id) && $key == $service_id ? ' selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        <span class="field-icon"><i class="fa fa-user"></i></span>
                                        <i class="arrow double"></i>
                                    </label>
                                    <span class="error-message"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button id="create_new_service_submit_button" type="button" class="btn btn-primary">Add Service</button>
                    </div>
                {!!Form::close() !!}
            </div>
        </div>
    </div>
@stop

@section('css-page-level-plugins')
    {!! Html::style($publicUrl . '/assets/global/plugins/select2/css/select2.min.css') !!}
    {!! Html::style($publicUrl . '/assets/global/plugins/select2/css/select2-bootstrap.min.css') !!}

    {!! Html::style($publicUrl . '/css/tomcss.css') !!}
@stop

@section('js-page-level-scripts')
    {!! Html::script($publicUrl .'/assets/global/plugins/select2/js/select2.full.min.js') !!}

@stop

@section('js-files')
    <script>
        $(function(){

            $('#button-create').click(function(){
                $('#modal_new_service').modal('show');
            });

            $('#create_new_service_submit_button').click(function(){
                var form = $('#createNewServiceForm');

                if (! validateJForm(form)) {
                    return false;
                }

               form.submit();
            });

            $('#sortable-body').sortable({
                containment: 'parent',
                start: function(event, ui){
                    $(ui.item.context).addClass('drag');
                },
                stop: function(event, ui){
                    $(ui.item.context).removeClass('drag');
                },
                update: function(event, ui){
                    $('#button-update-order').show();
                }
            });

            $('#button-update-order').hide();

            $('#button-update-order').click(function(ev){
                ev.preventDefault();

                var strCid = [];
                $('#sortable-body tr').each(function(){
                    strCid.push($(this).attr('data-id'));
                });

                $('#reorderStrCid').val(strCid.join(','));
                $('#reorderForm').submit();
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

            $('#service_id').change(function(ev){
                $('#striping_vendor_id').val('');

                if ($(this).val() == 18) {
                    $('#striping_vendor_container').removeClass('hidden');
                } else {
                    $('#striping_vendor_container').addClass('hidden');
                }
            });
        });

        function xeditCallback(el, response, newValue)
        {
            $('#grand_total').html(response.services_total_cost_with_discount_currency);
        }
    </script>
@stop
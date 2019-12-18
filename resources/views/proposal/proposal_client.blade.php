@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><a href="{{ route('proposal_list') }}">Proposals</a><i class="fa fa-angle-right"></i></li>
            <li><span>Details: <strong>Client</strong></span></li>
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
                                @include('proposal._tabs', ['current' => 'client', 'no_link' => true])

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
                                        <div class="row">
                                            <div class="col-md-7 static-info">
                                                <div class="name">Discount:</div>
                                                <div class="value">
                                                    @if (auth()->user()->hasPrivilege('update-proposal'))
                                                        <span title="Click to edit">
                                                            <a class="x-editable" href="#"
                                                               data-pk="{{ $proposal->id }}"
                                                               data-name="discount"
                                                               data-value="{{ $proposal->discount }}"
                                                               data-js-validation-function="isZeroOrPositive"
                                                               data-js-validation-error-message="Invalid entry"
                                                               data-php-validation-rule="zeroOrPositive"
                                                               data-type="text"
                                                               data-title="Discount:"
                                                               data-url="{{ route('proposal_inline_update') }}">
                                                                {{ $proposal->discount }}
                                                            </a>
                                                        </span>
                                                    @else
                                                        {{ $proposal->discount }}
                                                    @endif
                                                    %
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if ($proposal->statusHistory->count())
                                    <div class="portlet blue-hoki box">
                                        <div class="portlet-title">
                                            <div class="caption">History</div>
                                        </div>
                                        <div class="portlet-body">
                                            <div class="row plr10">
                                                <div class="col-sm-12 static-info table-scrollable plr0">
                                                    <table class="table table-striped table-bordered table-hover order-column" id="history_table">
                                                        <thead>
                                                            <tr>
                                                                <th><strong>Status</strong></th>
                                                                <th><strong>Set By</strong></th>
                                                                <th><strong>Set At</strong></th>
                                                                <th><strong>Comment</strong></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($proposal->statusHistory as $item)
                                                                <tr>
                                                                    <td>{{ $item->status->name }}</td>
                                                                    <td>{{ $item->setBy->fullName }}</td>
                                                                    <td>{{ $item->set_at->diffForHumans() }}</td>
                                                                    <td>{{ str_limit($item->comment, 200) }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="portlet blue-hoki box">
                                    <div class="portlet-title">
                                        <div class="caption">Assigned To</div>
                                    </div>
                                    @if (auth()->user()->hasPrivilege('update-proposal'))
                                        <div class="portlet-body">
                                            {!! Form::open(['url' => '#', 'id' => 'update_sales_managers_form']) !!}
                                                {!! Form::hidden('proposal_id', $proposal->id) !!}
                                                <div class="row">
                                                    <div class="col-sm-6 admin-form-item-widget">
                                                        {{ Form::jSelect('sales_manager_id', $salesManagersCB, ['label' => 'Sales Manager', 'selected' => $proposal->sales_manager_id ?? null, 'required' => false, 'iconClass' => 'fa fa-user', 'attributes' => ['id' => 'sales_manager_id']]) }}
                                                    </div>
                                                    <div class="col-sm-6 admin-form-item-widget">
                                                        <div id="sales_person_container">
                                                            {{ Form::jSelect('sales_person_id', $salesPersonsCB, ['label' => 'Sales Associate', 'selected' => $proposal->sales_person_id ?? null, 'required' => false, 'iconClass' => 'fa fa-user', 'attributes' => ['id' => 'sales_person_id']]) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            {!! Form::close() !!}

                                            {{--  Inline editable:
                                            <div class="row">
                                                <div class="col-sm-6 admin-form-item-widget">
                                                    <div class="show-container icon-class-container">
                                                        <span class="form-field-label">Sales Manager:</span>
                                                        <div class="form-field-value prepend-icon">
                                                            <span title="Click to edit">
                                                                <a class="x-editable" href="#"
                                                                   data-pk="{{ $proposal->id }}"
                                                                   data-name="sales_manager_id"
                                                                   data-value="{{ $proposal->sales_manager_id }}"
                                                                   data-source="{{ $json_sales_managers_cb }}"
                                                                   data-js-validation-function="isPositive"
                                                                   data-js-validation-error-message="Invalid type."
                                                                   data-php-validation-rule="required|positive"
                                                                   data-type="select"
                                                                   data-title="Sales Manager:"
                                                                   data-url="{{ route('proposal_inline_update') }}">
                                                                    {{ $proposal->salesManager->fullName ?? '&nbsp;' }}
                                                                </a>
                                                            </span>
                                                            <span class="field-icon"><i class="fa fa-user"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 admin-form-item-widget">
                                                    <div class="show-container icon-class-container">
                                                        <span class="form-field-label">Sales Associate:</span>
                                                        <div class="form-field-value prepend-icon">
                                                            <span title="Click to edit">
                                                                <a class="x-editable" href="#"
                                                                   data-pk="{{ $proposal->id }}"
                                                                   data-name="sales_person_id"
                                                                   data-value="{{ $proposal->sales_person_id }}"
                                                                   data-source="{{ $json_sales_persons_cb }}"
                                                                   data-js-validation-function="isPositive"
                                                                   data-js-validation-error-message="Invalid type."
                                                                   data-php-validation-rule="required|positive"
                                                                   data-type="select"
                                                                   data-title="Sales Associate:"
                                                                   data-url="{{ route('proposal_inline_update') }}">
                                                                    {{ $proposal->salesPerson->fullName ?? 'none' }}
                                                                </a>
                                                            </span>
                                                            <span class="field-icon"><i class="fa fa-user"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            --}}
                                        </div>
                                        <div class="">
                                            <div class="form-actions">
                                                <button id="submit_update_sales_managers_form_button" type="button" class="btn green not-yet-available">Update</button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="portlet-body">
                                            <div class="row">
                                                <div class="col-sm-6 admin-form-item-widget">
                                                    {{ Form::jShow($proposal->salesManager->fullName ?? '&nbsp;', ['label' => 'Sales Manager', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-user']) }}
                                                </div>
                                                <div class="col-sm-6 admin-form-item-widget">
                                                    <div id="sales_person_container">
                                                        {{ Form::jShow($proposal->salesPerson->fullName ?? '&nbsp;', ['label' => 'Sales Associate', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-user']) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="portlet blue-hoki box">
                                    <div class="portlet-title">
                                        <div class="caption">Proposal Name, Primary Contact Information</div>
                                    </div>
                                    <div class="portlet-body">
                                        {!! Form::open(['url' => '#', 'id' => 'update_contact_info_and_company_form']) !!}
                                            {!! Form::hidden('proposal_id', $proposal->id) !!}
                                            <div class="row">
                                                <div class="col-sm-12 admin-form-item-widget">
                                                    {{ Form::jText('name', ['value' => $proposal->name, 'label' => 'Proposal Name', 'id' => 'name', 'required' => false, 'iconClass' => 'fa fa-bookmark']) }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 admin-form-item-widget">
                                                    {{ Form::jSelect2('company_id', [], ['label' => 'Company Name', 'selected' => $proposal->company_id ?? null, 'required' => false, 'iconClass' => 'fa fa-building', 'attributes' => ['id' => 'company_id']]) }}
                                                </div>
                                                <div class="col-sm-6 xs-hidden admin-form-item-widget"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 admin-form-item-widget">
                                                    {{ Form::jText('billing_address', ['value' => ($proposal->company->billing_address ?? null), 'label' => 'Billing Address', 'id' => 'billing_address', 'required' => false, 'iconClass' => 'icon-location']) }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-3 admin-form-item-widget">
                                                    {{ Form::jText('billing_address_2', ['value' => ($proposal->company->billing_address_2 ?? null), 'label' => 'Billing Address 2', 'id' => 'billing_address_2', 'placeholder' => '', 'required' => false, 'iconClass' => 'icon-location']) }}
                                                </div>
                                                <div class="col-sm-6 admin-form-item-widget">
                                                    {{ Form::jText('billing_city', ['value' => ($proposal->company->billing_city ?? null), 'label' => 'Billing City', 'id' => 'billing_city', 'required' => false, 'iconClass' => 'icon-building']) }}
                                                </div>
                                                <div class="col-sm-3 admin-form-item-widget">
                                                    {{ Form::jText('billing_zipcode', ['value' => ($proposal->company->billing_zipcode ?? null), 'label' => 'Billing Zip Code', 'id' => 'billing_zipcode', 'required' => false, 'iconClass' => 'fa fa-map-o']) }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 admin-form-item-widget">
                                                    {{ Form::jSelect('billing_country_id', $countriesCB, ['label' => 'Billing Country', 'selected' => ($proposal->company->billing_country_id ?? null), 'required' => false, 'iconClass' => 'fa fa-globe', 'attributes' => ['id' => 'billing_country_id']]) }}
                                                </div>
                                                <div class="col-sm-6 admin-form-item-widget">
                                                    {{ Form::jSelect('billing_state_id', $statesCB, ['label' => 'Billing State', 'selected' => ($proposal->company->billing_state_id ?? null), 'required' => false, 'iconClass' => 'fa fa-map-pin', 'attributes' => ['id' => 'billing_state_id']]) }}
                                                </div>
                                            </div>
                                        <div class="row">
                                            <div class="col-sm-6 admin-form-item-widget">
                                                {{ Form::jSelect('manager_id', ($managersCB ?? []), ['label' => 'Primary Contact', 'selected' => $proposal->manager_id ?? null, 'required' => false, 'iconClass' => 'fa fa-user', 'attributes' => ['id' => 'manager_id']]) }}
                                            </div>
                                            <div class="col-sm-6 xs-hidden admin-form-item-widget"></div>
                                        </div>
                                            <div class="row">
                                                <div class="col-sm-4 admin-form-item-widget">
                                                    {{ Form::jText('email', ['value' => $proposal->manager->email ?? '', 'label' => 'Primary Email', 'hint' => '(used for notifications)', 'id' => 'email', 'required' => false, 'iconClass' => 'fa fa-envelope']) }}
                                                </div>
                                                <div class="col-sm-4 admin-form-item-widget">
                                                    {{ Form::jText('phone', ['value' => $proposal->manager->phone ?? '', 'label' => 'Primary Phone', 'id' => 'phone', 'required' => false, 'iconClass' => 'fa fa-phone']) }}
                                                </div>
                                                <div class="col-sm-4 admin-form-item-widget">
                                                    {{ Form::jText('alt_phone', ['value' => $proposal->manager->alt_phone ?? '', 'label' => 'Alt Phone', 'id' => 'alt_phone', 'required' => false, 'iconClass' => 'fa fa-phone']) }}
                                                </div>
                                            </div>

                                        {!! Form::close() !!}
                                    </div>
                                    <div class="">
                                        <div class="form-actions">
                                            <button id="submit_update_contact_info_and_company_form_button" type="button" class="btn green">Update</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="portlet blue-hoki box">
                                    <div class="portlet-title">
                                        <div class="caption">Job Location</div>
                                    </div>
                                    <div class="portlet-body">
                                        {!! Form::open(['url' => '#', 'id' => 'update_job_location_form']) !!}
                                            {!! Form::hidden('proposal_id', $proposal->id) !!}
                                            <div class="row">
                                                <div class="col-sm-12 admin-form-item-widget">
                                                    {{ Form::jShow($proposal->property->name ?? '&nbsp;', ['label' => 'Property Name', 'class' => 'icon-class-container', 'iconClass' => 'fa fa-building']) }}
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-12 admin-form-item-widget">
                                                    {{ Form::jText('address', ['value' => ($proposal->address ?? null), 'label' => 'Address', 'id' => 'address', 'required' => false, 'iconClass' => 'icon-location']) }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-3 admin-form-item-widget">
                                                    {{ Form::jText('address_2', ['value' => ($proposal->address_2 ?? null), 'label' => 'Address 2', 'id' => 'address_2', 'placeholder' => '', 'required' => false, 'iconClass' => 'icon-location']) }}
                                                </div>
                                                <div class="col-sm-6 admin-form-item-widget">
                                                    {{ Form::jText('city', ['value' => ($proposal->city ?? null), 'label' => 'City', 'id' => 'city', 'required' => false, 'iconClass' => 'icon-building']) }}
                                                </div>
                                                <div class="col-sm-3 admin-form-item-widget">
                                                    {{ Form::jText('zipcode', ['value' => ($proposal->zipcode ?? null), 'label' => 'Zip Code', 'id' => 'zipcode', 'required' => false, 'iconClass' => 'fa fa-map-o']) }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 admin-form-item-widget">
                                                    {{ Form::jSelect('country_id', $countriesCB, ['label' => 'Country', 'selected' => ($proposal->country_id ?? null), 'required' => false, 'iconClass' => 'fa fa-globe', 'attributes' => ['id' => 'country_id']]) }}
                                                </div>
                                                <div class="col-sm-6 admin-form-item-widget">
                                                    {{ Form::jSelect('state_id', $statesCB, ['label' => 'State', 'selected' => ($proposal->state_id ?? null), 'required' => false, 'iconClass' => 'fa fa-map-pin', 'attributes' => ['id' => 'state_id']]) }}
                                                </div>
                                            </div>
                                        {!! Form::close() !!}
                                    </div>
                                    <div class="">
                                        <div class="form-actions">
                                            <button id="submit_update_job_location_form_button" type="button" class="btn green">Update</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="portlet blue-hoki box">
                                    <div class="portlet-title">
                                        <div class="caption">Notes</div>
                                    </div>
                                    <div class="portlet-body">
                                        <div id="notes_list_container" class="{{ $proposal->notes->count() ? '' : 'hidden' }}">
                                            <h3 class="mtb5 fs17">Notes:</h3>
                                            <div class="row plr10">
                                                <div class="col-sm-12 static-info table-scrollable plr0">
                                                    <table class="table table-striped table-bordered table-hover order-column" id="notes_table">
                                                        <thead>
                                                        <tr>
                                                            <th class="w140 tc"><strong>Date</strong></th>
                                                            <th><strong>Note</strong></th>
                                                            <th class="tc"><strong>Created By</strong></th>
                                                            <th class="tc"><strong>Remainder Set</strong></th>
                                                            <th class="tc"><strong>Show</strong></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach ($proposal->notes as $note)
                                                            <tr>
                                                                <td class="tc">{{ !empty($note->created_at) ? $note->created_at->format('d M Y') : '' }}</td>
                                                                <td>{{ str_limit($note->note, 200) }}</td>
                                                                <td class="tc">{{ $note->createdBy->fullName ?? '' }}</td>
                                                                <td class="tc">{{ !empty($note->remainded_at) ? $note->remainded_at->format('d M Y') : '' }}</td>
                                                                <td class="tc">
                                                                    <a href="javascript:;" class="show-note-link" data-toggle="tooltip" title="Show note"><i class="fa fa-eye"></i></a>
                                                                    <div class="hidden note-content">{!! $note->note !!}</div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <h3 class="mtb10 fs16">Create Note:</h3>

                                        {!! Form::open(['url' => '#', 'id' => 'add_note_form']) !!}
                                            {!! Form::hidden('proposal_id', $proposal->id) !!}

                                            <div class="row pt5">
                                                <div class="col-md-8 admin-form-item-widget">
                                                    {{ Form::jTextarea('note', ['label' => 'New Note', 'id' => 'note', 'required' => false, 'iconClass' => 'icon-bookmark']) }}
                                                </div>
                                                <div class="col-md-4 xs-hidden"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4 admin-form-item-widget">
                                                    {{ Form::jDateTimePicker('remainded_at', ['label' => 'Set Remainder', 'hint' => '(blank: do not remaind)', 'id' => 'remainded_at', 'required' => false, 'iconClass' => 'fa fa-calendar']) }}
                                                </div>
                                                <div class="col-md-4 admin-form-item-widget">
                                                    <div class="switch-container pt27">
                                                        {{ Form::jSwitch('share_note', ['label' => 'Share Note with Customer', 'id' => 'share_note']) }}
                                                    </div>
                                                </div>
                                                <div class="col-md-4 admin-form-item-widget"></div>
                                            </div>
                                        {!! Form::close() !!}
                                    </div>
                                    <div class="">
                                        <div class="form-actions">
                                            <button id="submit_add_note_form_button" type="button" class="btn green">Save New Note</button>
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

    <div id="modal_show_note" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content jform-container">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                    <h4 class="modal-title">Note</h4>
                </div>
                <div class="modal-body">
                    <div class="note-container pt15 pb10"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
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
            // modal_show_note note-container

            $('body').on('click', '.show-note-link', function(){
                $('#modal_show_note .note-container').html($(this).parents('td').find('.note-content').html());
                $('#modal_show_note').modal('show');
            });

            var htmlCompaniesCB = {!! $json_html_property_management_companies_cb !!};

            $('#company_id').select2({
                data: htmlCompaniesCB,
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

            $('body').on('change', '#company_id', function(ev){
                if ($(this).val() > 0) {
                    $('#company_id-error').remove();
                }
            });

            @if (!empty($proposal->company_id))
                $('#company_id').val("{{ $proposal->company_id }}").trigger('change.select2');
            @endif

            var htmlPropertiesCB = {!! $json_html_properties_cb !!};

            $('#property_id').select2({
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

            $('body').on('change', '#property_id', function(ev){
                if ($(this).val() > 0) {
                    $('#property_id-error').remove();
                }
            });

            @if (!empty($proposal->property_id))
                $('#property_id').val("{{ $proposal->property_id }}").trigger('change.select2');
            @endif


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
            
            $('#billing_country_id').change(function(ev){
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
                        $('#billing_state_id').empty();
                        if (response.success) {
                            var html = Object.keys(response.data).length > 0 ? ['<option value="0">Select state</option>'] : [];
                            $.each(response.data, function(index, value){
                                html.push('<option value="'+ index +'">'+ value +'</option>')
                            });
                            $('#billing_state_id').html(html.join(''));
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

            $('#company_id').change(function(ev){
                $.ajax({
                    type:"post",
                    url: "{{ route('ajax_company_details_fetch') }}",
                    data: {
                        company_id: $(this).val()
                    },
                    beforeSend: function (request){
                        PNotify.removeAll();
                        showSpinner();
                    },
                    complete: function(){
                        hideSpinner();
                    },
                    success: function(response){
                        if (response.success) {
                            $('#billing_address').val(response.company.billing_address);
                            $('#billing_address_2').val(response.company.billing_address_2);
                            $('#billing_city').val(response.company.billing_city);
                            $('#billing_zipcode').val(response.company.billing_zipcode);
                            $('#billing_country_id').val(response.company.billing_country_id);

                            $('#billing_state_id').empty();
                            var html = Object.keys(response.billing_states_cb).length > 0 ? ['<option value="0">Select State</option>'] : [];
                            $.each(response.billing_states_cb, function(index, value){
                                html.push('<option value="'+ index +'">'+ value +'</option>')
                            });
                            $('#billing_state_id').html(html.join(''));
                            $('#billing_state_id').val(response.company.billing_state_id);


                            $('#manager_id').empty();
                            if (typeof response.company_users_cb.length == 'undefined') {  // means is an object and not empty array
                                var html = [];
                                var html = Object.keys(response.company_users_cb).length > 0 ? ['<option value="0"></option>'] : [];
                                $.each(response.company_users_cb, function(index, value){
                                    html.push('<option value="'+ index +'">'+ value +'</option>')
                                });
                                $('#manager_id').html(html.join(''));
                                $('#manager_id').prop("selectedIndex", 0);
                            }

                            $('#email').val('');
                            $('#phone').val('');
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

            $('#manager_id').change(function(){
                $.ajax({
                    url : "{{ route('ajax_user_email_and_phone_fetch') }}",
                    type: 'post',
                    data: {
                        user_id: $(this).val()
                    },
                    beforeSend: function (request){
                        showSpinner();
                    },
                    complete: function(){
                        hideSpinner();
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#email').val(response.email);
                            $('#phone').val(response.phone);
                        }
                    },
                    error: function(data) {
                        hideSpinner();
                        console.log(data);
                    }
                });
            });

            $('#submit_update_sales_managers_form_button').click(function(ev){
                $.ajax({
                    url: "{{ route('ajax_proposal_client_update_managers') }}",
                    type:"post",
                    data: $('#update_sales_managers_form').serialize(),
                    beforeSend: function (request){
                        PNotify.removeAll();
                        showSpinner();
                    },
                    complete: function(){
                        hideSpinner();
                    },
                    success: function(response){
                        if (response.success) {
                            notify({
                                type: 'success',
                                title: 'Success',
                                message: '"Assigned To" information successfully updated.',
                                addClass: 'mt50',
                                delay: 3000
                            });
                        } else {
                            notify({
                                type: 'error',
                                title: 'Error',
                                message: response.message,
                                addClass: 'mt50',
                                delay: 5000
                            });
                        }
                    }
                });
            });

            $('#submit_update_contact_info_and_company_form_button').click(function(ev){
                $.ajax({
                    url: "{{ route('ajax_proposal_client_update_contact_info') }}",
                    type:"post",
                    data: $('#update_contact_info_and_company_form').serialize(),
                    beforeSend: function (request){
                        PNotify.removeAll();
                        showSpinner();
                    },
                    complete: function(){
                        hideSpinner();
                    },
                    success: function(response){
                        if (response.success) {
                            $('#porlet_caption_proposal_name').text($('#name').val());

                            notify({
                                type: 'success',
                                title: 'Success',
                                message: '"Primary Contact Information" successfully updated.',
                                addClass: 'mt50',
                                delay: 3000
                            });
                        } else {
                            notify({
                                type: 'error',
                                title: 'Error',
                                message: response.message,
                                addClass: 'mt50',
                                delay: 5000
                            });
                        }
                    }
                });
            });

            $('#submit_update_job_location_form_button').click(function(ev){
                $.ajax({
                    url: "{{ route('ajax_proposal_client_job_location') }}",
                    type:"post",
                    data: $('#update_job_location_form').serialize(),
                    beforeSend: function (request){
                        PNotify.removeAll();
                        showSpinner();
                    },
                    complete: function(){
                        hideSpinner();
                    },
                    success: function(response){
                        $('#porlet_caption_property_name').text($('#property_id option:selected').text());

                        if (response.success) {
                            notify({
                                type: 'success',
                                title: 'Success',
                                message: '"Job Location" information successfully updated.',
                                addClass: 'mt50',
                                delay: 3000
                            });
                        } else {
                            notify({
                                type: 'error',
                                title: 'Error',
                                message: response.message,
                                addClass: 'mt50',
                                delay: 5000
                            });
                        }
                    }
                });
            });

            $('#submit_add_note_form_button').click(function(ev){
                $.ajax({
                    url: "{{ route('ajax_proposal_client_add_note') }}",
                    type:"post",
                    data: $('#add_note_form').serialize(),
                    beforeSend: function (request){
                        PNotify.removeAll();
                        showSpinner();
                    },
                    complete: function(){
                        hideSpinner();

                        $('#note').val('');
                        $('#remainded_at').val('');
                        $('#share_note').removeAttr('checked');
                    },
                    success: function(response){
                        if (response.success) {
                            var html = '';

                            html += '<tr>';
                            html += '<td class="tc">'+ response.date +'</td>';
                            html += '<td>'+ response.excerpt +'</td>';
                            html += '<td class="tc">'+ response.creator +'</td>';
                            html += '<td class="tc">'+ response.remainded_at +'</td>';
                            html += '<td class="tc">';
                            html += '<a href="javascript:;" class="show-note-link" data-toggle="tooltip" title="Show note"><i class="fa fa-eye"></i></a>';
                            html += '<div class="hidden note-content">'+ response.note +'</div>';
                            html += '</td>';
                            html += '</tr>';

                            $('#notes_list_container tbody').append(html);

                            $('#notes_list_container').removeClass('hidden');

                            notify({
                                type: 'success',
                                title: 'Success',
                                message: 'Note successfully added.',
                                addClass: 'mt50',
                                delay: 3000
                            });
                        } else {
                            notify({
                                type: 'error',
                                title: 'Error',
                                message: response.message,
                                addClass: 'mt50',
                                delay: 5000
                            });
                        }
                    }
                });
            });
        });

    </script>
@stop
{{-- $subContractors --}}
<div class="portlet blue-hoki box" id="sub_contractors_section">
    <div class="portlet-title">
        <div class="caption">Sub Contractors</div>
    </div>
    <div class="portlet-body">
        <form action="#" id="sub_contractor_dummy_form">
            <div class="row">
                <div class="col-sm-12 admin-form-item-widget">
                    <table class="table table-bordered list-table sortable-table">
                        <thead>
                        <tr>
                            <th class="td-checkbox">
                                <div class="radio-checkbox-container vertical">
                                    <label class="radio-checkbox-item">
                                        <input type="checkbox" id="sub_contractor_toggle_checkboxes" class="toggle_checkboxes">
                                        <i class="fa fa-square-o"></i>
                                        <i class="fa fa-check-square-o"></i>
                                    </label>
                                </div>
                            </th>
                            <th class="tl">Sub Contractor</th>
                            <th class="tl">Description of Service</th>
                            <th class="td-number tc">Over Head (%)</th>
                            <th class="td-number">Quoted Cost</th>
                            <th class="w90 tc">Have Bid</th>
                        </tr>
                        </thead>
                        <tbody class="multi-section">
                            @foreach ($obj as $subContractor)
                                @php
                                    $existingSubContractor = $orderService->subContactors()->where('sub_contractor_id', $subContractor->id)->first();
                                @endphp
                                <tr data-id="{{ $subContractor->id }}">
                                    <td class="td-checkbox tt">
                                        <div class="radio-checkbox-container vertical">
                                            <label for="sub_contractor_id_{{ $subContractor->id }}" class="radio-checkbox-item">
                                                <input id="sub_contractor_id_{{ $subContractor->id }}" class="checkbox" type="checkbox" value="{{ $subContractor->id }}"{{ !empty($existingSubContractor->id) ? ' checked' : '' }}>
                                                <i class="fa fa-square-o"></i>
                                                <i class="fa fa-check-square-o"></i>
                                            </label>
                                        </div>
                                        {!! Form::hidden('sub_contractor_id[]', $subContractor->id) !!}
                                    </td>
                                    <td class="tl tt">{{ $subContractor->full_name }}</td>
                                    <td class="">
                                        <label for="sub_contractor_description_{{ $subContractor->id }}" class="field">
                                            <textarea class="gui-input gui-textarea description tl" id="sub_contractor_description_{{ $subContractor->id }}" name="sub_contractor_description[]">{{ $existingSubContractor->description ?? '' }}</textarea>
                                        </label>
                                    </td>
                                    <td class="td-number tt">
                                        <label for="sub_contractor_over_head_{{ $subContractor->id }}" class="field">
                                            <input class="gui-input over_head tc" id="sub_contractor_over_head_{{ $subContractor->id }}" name="sub_contractor_over_head[]" type="text" value="{{ $subContractor->overhead }}">
                                        </label>
                                    </td>
                                    <td class="td-number tt">
                                        <label for="sub_contractor_quoted_cost_{{ $subContractor->id }}" class="field">
                                            <input class="gui-input quoted_cost tc" id="sub_contractor_quoted_cost_{{ $subContractor->id }}" name="sub_contractor_quoted_cost[]" type="text" value="{{ $existingSubContractor->cost ?? '' }}">
                                        </label>
                                    </td>
                                    <td class="tt tc">
                                        <div class="radio-checkbox-container vertical">
                                            <label for="sub_contractor_have_bid_{{ $subContractor->id }}" class="radio-checkbox-item">
                                                <input id="sub_contractor_have_bid_{{ $subContractor->id }}" class="checkbox-have-bid" type="checkbox" value="1">
                                                <i class="fa fa-square-o"></i>
                                                <i class="fa fa-check-square-o"></i>
                                            </label>
                                        </div>
                                        {!! Form::hidden('sub_contractor_have_bid[]', null, ['class' => 'sub-contractor-have-bid']) !!}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 xs-hidden tl">
                </div>
                <div class="col-sm-6 tr">
                    Total Sub Contractor Costs: <strong>$<span id="section_sub_contractor_total_cost" class="section-total-cost">0.00</span></strong>
                </div>
            </div>
        </form>
    </div>
</div>
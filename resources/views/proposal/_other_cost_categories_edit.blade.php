{{-- $otherCostCategories --}}
<div class="portlet blue-hoki box" id="other_costs_section">
    <div class="portlet-title">
        <div class="caption">Other Costs</div>
    </div>
    <div class="portlet-body">
        <div class="row">
            <div class="col-sm-12 admin-form-item-widget">
                <table class="table table-bordered list-table sortable-table">
                    <thead>
                    <tr>
                        <th class="td-checkbox">
                            <div class="radio-checkbox-container vertical">
                                <label class="radio-checkbox-item">
                                    <input type="checkbox" id="other_cost_category_toggle_checkboxes" class="toggle_checkboxes">
                                    <i class="fa fa-square-o"></i>
                                    <i class="fa fa-check-square-o"></i>
                                </label>
                            </div>
                        </th>
                        <th class="tl">Add Additional Costs</th>
                        <th class="">Description</th>
                        <th class="td-number">Cost</th>
                    </tr>
                    </thead>
                    <tbody class="multi-section">
                        @foreach ($obj as $otherCostCategory)
                            @php
                                $existingOtherCostCategory = $orderService->otherCosts()->where('other_cost_category_id', $otherCostCategory->id)->first();
                            @endphp
                            <tr data-id="{{ $otherCostCategory->id }}">
                                <td class="td-checkbox tt">
                                    <div class="radio-checkbox-container vertical">
                                        <label for="other_cost_category_id_{{ $otherCostCategory->id }}" class="radio-checkbox-item">
                                            <input id="other_cost_category_id_{{ $otherCostCategory->id }}" class="checkbox" type="checkbox" value="{{ $otherCostCategory->id }}"{{ !empty($existingOtherCostCategory->id) ? ' checked' : '' }}>
                                            <i class="fa fa-square-o"></i>
                                            <i class="fa fa-check-square-o"></i>
                                        </label>
                                    </div>
                                    {!! Form::hidden('other_cost_category_id[]', $otherCostCategory->id) !!}
                                </td>
                                <td class="tl tt">{{ $otherCostCategory->name }}</td>
                                <td class="">
                                    <label for="other_cost_category_description_{{ $otherCostCategory->id }}" class="field">
                                        <textarea class="gui-input gui-textarea description tl" id="other_cost_category_description_{{ $otherCostCategory->id }}" name="other_cost_category_description[]">{{ $existingOtherCostCategory->description ?? '' }}</textarea>
                                    </label>
                                </td>
                                <td class="td-number tt">
                                    <label for="other_cost_category_cost_{{ $otherCostCategory->id }}" class="field">
                                        <input class="gui-input cost tc" id="other_cost_category_cost_{{ $otherCostCategory->id }}" name="other_cost_category_cost[]" type="text" value="{{ $existingOtherCostCategory->cost ?? '' }}">
                                    </label>
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
                Total Other Costs: <strong>$<span id="section_other_cost_category_total_cost" class="section-total-cost">0.00</span></strong>
            </div>
        </div>
    </div>
</div>
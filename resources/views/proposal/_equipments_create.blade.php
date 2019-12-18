{{-- $service->category->equipments --}}
@if ($obj->count())
    <div class="portlet blue-hoki box" id="equipment_section">
        <div class="portlet-title">
            <div class="caption">Equipment Costs</div>
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
                                        <input type="checkbox" id="equipment_toggle_checkboxes" class="toggle_checkboxes">
                                        <i class="fa fa-square-o"></i>
                                        <i class="fa fa-check-square-o"></i>
                                    </label>
                                </div>
                            </th>
                            <th class="tl">Equipment</th>
                            <th class="td-currency">Rate</th>
                            <th class="td-number">Quantity</th>
                            <th class="td-number">Days</th>
                            <th class="td-number">Hours per Day</th>
                            <th class="td-number">Min Cost</th>
                        </tr>
                        </thead>
                        <tbody class="multi-section">
                        @foreach ($obj as $equipment)
                            <tr data-id="{{ $equipment->id }}" data-cost="{{ $equipment->cost }}" data-min_cost="{{ $equipment->min_cost }}" data-rate_type_id="{{ $equipment->rate_type_id }}">
                                <td class="td-checkbox">
                                    <div class="radio-checkbox-container vertical">
                                        <label for="equipment_id_{{ $equipment->id }}"  class="radio-checkbox-item">
                                            <input id="equipment_id_{{ $equipment->id }}" class="checkbox" type="checkbox" value="{{ $equipment->id }}"{{ !empty($equipment->pivot->is_default) ? ' checked' : '' }}>
                                            <i class="fa fa-square-o"></i>
                                            <i class="fa fa-check-square-o"></i>
                                        </label>
                                    </div>
                                    {!! Form::hidden('equipment_id[]', $equipment->id) !!}
                                    {!! Form::hidden('equipment_rate_type_id[]', $equipment->rate_type_id) !!}
                                    {!! Form::hidden('equipment_name[]', $equipment->name) !!}
                                    {!! Form::hidden('equipment_cost[]', $equipment->cost) !!}
                                    {!! Form::hidden('equipment_min_cost[]', $equipment->min_cost) !!}
                                </td>
                                <td class="tl">{{ $equipment->name }}</td>
                                <td class="td-currency">{{ $equipment->loads }} {{ $equipment->rateType->name }}</td>
                                <td class="td-number">
                                    <label for="equipment_quantity_{{ $equipment->id }}" class="field">
                                        <input class="gui-input quantity tc" id="equipment_quantity_{{ $equipment->id }}" name="equipment_quantity[]" type="text">
                                    </label>
                                </td>
                                <td class="td-number">
                                    <label for="equipment_days_needed_{{ $equipment->id }}" class="field">
                                        <input class="gui-input days-needed tc" id="equipment_days_needed_{{ $equipment->id }}" name="equipment_days_needed[]" type="text">
                                    </label>
                                </td>
                                <td class="td-number">
                                    <label for="equipment_hours_per_day_{{ $equipment->id }}" class="field">
                                        @if ($equipment->rate_type_id != 1)
                                            <span>-</span>
                                            {{--
                                            <input class="gui-input hours-per-day tc" id="equipment_hours_per_day_{{ $equipment->id }}" name="equipment_hours_per_day[]" type="text" placeholder="N/A" readonly>
                                            --}}
                                            <input name="equipment_hours_per_day[]" type="hidden" value="">
                                        @else
                                            <input class="gui-input hours-per-day tc" id="equipment_hours_per_day_{{ $equipment->id }}" name="equipment_hours_per_day[]" type="text" required="true">
                                        @endif
                                    </label>
                                </td>
                                <td class="td-number">{{ $equipment->min_cost_currency }}</td>
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
                    Total Equipment Cost: <strong>$<span id="section_equipment_total_cost" class="section-total-cost">0.00</span></strong>
                </div>
            </div>
        </div>
    </div>
@endif
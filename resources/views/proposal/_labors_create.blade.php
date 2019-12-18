{{-- $labors --}}
<div class="portlet blue-hoki box" id="labor_rates_section">
    <div class="portlet-title">
        <div class="caption">Labor Costs</div>
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
                                    <input type="checkbox" id="labor_toggle_checkboxes" class="toggle_checkboxes">
                                    <i class="fa fa-square-o"></i>
                                    <i class="fa fa-check-square-o"></i>
                                </label>
                            </div>
                        </th>
                        <th class="tl">Labor</th>
                        <th class="td-currency">Rate</th>
                        <th class="td-number">Quantity</th>
                        <th class="td-number">Days</th>
                        <th class="td-number">Hours per Day</th>
                    </tr>
                    </thead>
                    <tbody class="multi-section">
                    @foreach ($obj as $labor)
                        <tr data-id="{{ $labor->id }}" data-rate="{{ $labor->rate }}">
                            <td class="td-checkbox">
                                <div class="radio-checkbox-container vertical">
                                    <label for="labor_id_{{ $labor->id }}" class="radio-checkbox-item">
                                        <input id="labor_id_{{ $labor->id }}" class="checkbox" type="checkbox" value="{{ $labor->id }}">
                                        <i class="fa fa-square-o"></i>
                                        <i class="fa fa-check-square-o"></i>
                                    </label>
                                </div>
                                {!! Form::hidden('labor_id[]', $labor->id) !!}
                                {!! Form::hidden('labor_name[]', $labor->name) !!}
                                {!! Form::hidden('labor_rate[]', $labor->rate) !!}
                            </td>
                            <td class="tl">{{ $labor->name }}</td>
                            <td class="td-currency">{{ $labor->rate_currency }} per hour</td>
                            <td class="td-number">
                                <label for="labor_quantity_{{ $labor->id }}" class="field">
                                    <input class="gui-input quantity tc" id="labor_quantity_{{ $labor->id }}" name="labor_quantity[]" type="text">
                                </label>
                            </td>
                            <td class="td-number">
                                <label for="labor_days_needed_{{ $labor->id }}" class="field">
                                    <input class="gui-input days-needed tc" id="labor_days_needed_{{ $labor->id }}" name="labor_days_needed[]" type="text">
                                </label>
                            </td>
                            <td class="td-number">
                                <label for="labor_hours_per_day_{{ $labor->id }}" class="field">
                                    <input class="gui-input hours-per-day tc" id="labor_hours_per_day_{{ $labor->id }}" name="labor_hours_per_day[]" type="text">
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
                Total Labor Cost: <strong>$<span id="section_labor_total_cost" class="section-total-cost">0.00</span></strong>
            </div>
        </div>
    </div>
</div>
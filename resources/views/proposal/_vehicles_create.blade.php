{{-- $service->category->vehicleTypes --}}
@if ($obj->count())
    <div class="portlet blue-hoki box" id="vehicle_types_section">
        <div class="portlet-title">
            <div class="caption">Vehicle Costs</div>
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
                                        <input type="checkbox" id="vehicle_type_toggle_checkboxes" class="toggle_checkboxes">
                                        <i class="fa fa-square-o"></i>
                                        <i class="fa fa-check-square-o"></i>
                                    </label>
                                </div>
                            </th>
                            <th class="tl">Vehicle Type</th>
                            <th class="td-currency">Rate</th>
                            <th class="td-number">Quantity</th>
                            <th class="td-number">Days</th>
                            <th class="td-number">Hours per Day</th>
                        </tr>
                        </thead>
                        <tbody class="multi-section">
                        @foreach ($obj as $vehicleType)
                            <tr data-id="{{ $vehicleType->id }}" data-rate="{{ $vehicleType->rate }}">
                                <td class="td-checkbox">
                                    <div class="radio-checkbox-container vertical">
                                        <label for="vehicle_type_id_{{ $vehicleType->id }}" class="radio-checkbox-item">
                                            <input id="vehicle_type_id_{{ $vehicleType->id }}" class="checkbox" type="checkbox" value="{{ $vehicleType->id }}"{{ !empty($vehicleType->pivot->is_default) ? ' checked' : '' }} >
                                            <i class="fa fa-square-o"></i>
                                            <i class="fa fa-check-square-o"></i>
                                        </label>
                                    </div>
                                    {!! Form::hidden('vehicle_type_id[]', $vehicleType->id) !!}
                                    {!! Form::hidden('vehicle_rate[]', $vehicleType->rate) !!}
                                    {!! Form::hidden('vehicle_name[]', $vehicleType->name) !!}
                                </td>
                                <td class="tl">{{ $vehicleType->name }}</td>
                                <td class="td-currency">{{ $vehicleType->rate_currency }} per hour</td>
                                <td class="td-number">
                                    <label for="vehicle_type_quantity_{{ $vehicleType->id }}" class="field">
                                        <input class="gui-input quantity tc" id="vehicle_type_quantity_{{ $vehicleType->id }}" name="vehicle_type_quantity[]" type="text">
                                    </label>
                                </td>
                                <td class="td-number">
                                    <label for="vehicle_type_days_needed_{{ $vehicleType->id }}" class="field">
                                        <input class="gui-input days-needed tc" id="vehicle_type_days_needed_{{ $vehicleType->id }}" name="vehicle_type_days_needed[]" type="text">
                                    </label>
                                </td>
                                <td class="td-number">
                                    <label for="vehicle_type_hours_per_day_{{ $vehicleType->id }}" class="field">
                                        <input class="gui-input hours-per-day tc" id="vehicle_type_hours_per_day_{{ $vehicleType->id }}" name="vehicle_type_hours_per_day[]" type="text">
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
                    Total Vehicle Cost: <strong>$<span id="section_vehicle_type_total_cost" class="section-total-cost">0.00</span></strong>
                </div>
            </div>
        </div>
    </div>
@endif
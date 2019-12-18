{{-- $stripingServiceCategories --}}
@if ($obj->count())
    <div class="portlet blue-hoki box" id="striping_services_section">
        <div class="portlet-title">
            <div class="caption">{{ $stripingVendor->name }} - Services</div>
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
                                        <input type="checkbox" id="striping_service_toggle_checkboxes" class="toggle_checkboxes">
                                        <i class="fa fa-square-o"></i>
                                        <i class="fa fa-check-square-o"></i>
                                    </label>
                                </div>
                            </th>
                            <th class="tl">Category</th>
                            <th class="tl">Name</th>
                            <th class="td-number">Price</th>
                            <th class="td-number">Quantity</th>
                        </tr>
                        </thead>
                        <tbody class="multi-section">
                            @foreach ($obj as $stripingServiceCategory)
                                @foreach ($stripingServiceCategory->stripingServices as $stripingService)
                                    @php
                                        $existingObj = $orderService->stripingVendorServices()->where('striping_service_id', $stripingService->id)->first();
                                    @endphp
                                    <tr data-id="{{ $stripingService->id }}" data-price="{{ $stripingService->stripingVendors->first()->price }}">
                                        <td class="td-checkbox">
                                            <div class="radio-checkbox-container vertical">
                                                <label for="striping_service_id_{{ $stripingService->id }}" class="radio-checkbox-item">
                                                    <input id="striping_service_id_{{ $stripingService->id }}" class="checkbox" type="checkbox" value="{{ $stripingService->id }}"{{ !empty($existingObj->id) ? ' checked' : '' }}>
                                                    <i class="fa fa-square-o"></i>
                                                    <i class="fa fa-check-square-o"></i>
                                                </label>
                                            </div>
                                            {!! Form::hidden('striping_service_id[]', $stripingService->id) !!}
                                            {!! Form::hidden('striping_service_price[]', $stripingService->stripingVendors->first()->price) !!}
                                        </td>
                                        <td class="tl">{{ $stripingService->stripingServiceCategory->name }}</td>
                                        <td class="tl">{{ $stripingService->name }}</td>
                                        <td class="td-currency">{{ $stripingService->stripingVendors->first()->price_currency }}</td>
                                        <td class="td-number">
                                            <label for="striping_service_quantity_{{ $stripingService->id }}" class="field">
                                                <input class="gui-input quantity tc" id="striping_service_quantity_{{ $stripingService->id }}" name="striping_service_quantity[]" type="text" value="{{ $existingObj->quantity ?? '' }}">
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 xs-hidden tl">
                </div>
                <div class="col-sm-6 tr">
                    Total Striping Services Cost: <strong>$<span id="section_striping_service_total_cost" class="section-total-cost">0.00</span></strong>
                </div>
            </div>
        </div>
    </div>
@endif
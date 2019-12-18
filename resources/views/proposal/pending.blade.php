@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>Proposals: <strong>Pending</strong></span></li>
    </ul>
@stop

@section('content')
    <div class="page-content-inner">
        <div class="row">
            <div class="col-md-12">
                @include('errors._list')
                <div class="portlet box list-items admin-form">
                    <div class="portlet-body">
                        <div class="clearfix">
                            <div class="col-sm-12 col-md-7 pl0 ml0 pr0 mr0 mb15">
                                <span class="select-container">
                                    <label for="aging_filter" class="select-label">Aging</label>:
                                    <select id="aging_filter">
                                        @foreach ($agingCB as $key => $value)
                                            <option value="{{ $key }}"{!! !empty($agingId) && $agingId == $key ? ' selected="selected"' : ''!!} data-url="{{ route('proposal_list', array_merge(Request::query(), ['agingId' => $key])) }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </span>
                            </div>
                            <div class="col-sm-12 col-md-5 pl0 ml0 pr0 mr0 mb15 search-container">
                                @if (auth()->user()->hasPrivilege('search-proposal'))
                                    {!! Form::jSearchForm($needle, route('proposal_search'), route('proposal_list')) !!}
                                @endif
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered list-table scrollable">
                                <thead>
                                    <tr>
                                        <th class="td-sortable tc w120">{!! SortableTrait::link('created_at', 'Aging') !!}</th>
                                        <th class="td-sortable td-name">{!! SortableTrait::link('name', 'Name') !!}</th>
                                        <th class="td-sortable td-name">{!! SortableTrait::link('orders.property_id|properties.name', 'Property') !!}</th>
                                        <th class="td-name">Location</th>
                                        <th class="td-sortable td-name">{!! SortableTrait::link('orders.company_id|companies.name', 'Company') !!}</th>
                                        <th class="actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="tbfs12">
                                @foreach ($proposals as $proposal)
                                    <tr data-id="{{ $proposal->id }}" class="not-tag {{ !empty($proposal->alert) ? ' alert' : (!empty($proposal->disabled) ? ' disabled' : (!empty($proposal->status_id) ? ' status-'. $proposal->status_id : '')) }}"{!! !empty($proposal->aged_period->background_color) ? ' style="background-color:'. $proposal->aged_period->background_color .'"' : '' !!}>
                                        <td class="td-date prel tc td-icon">
                                            <div class="ribbon"></div>
                                            {!! $proposal->aged_icon !!}<div class="fs12">{{ $proposal->created_at->format('M. d, Y') ?? '' }}</div>
                                        </td>
                                        <td class="td-name">{{ $proposal->name }}</td>
                                        <td class="td-name">{{ $proposal->property->name ?? 'unknown' }}</td>
                                        <td class="td-name">{!! $proposal->full_location ?? 'unknown' !!}</td>
                                        <td class="td-name">{{ $proposal->company->name ?? 'unknown' }}</td>
                                        <td class="centered actions all-time-visible-actions">
                                            @if (auth()->user()->hasPrivilege('update-proposal'))
                                                <a href="javascript:void(0);" class="action" data-action="route" data-route="{{ route('proposal_details_client', ['id' => $proposal->id]) }}">Edit</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {!! Form::jPaginator($proposals, 'proposal_list') !!}

                        {!! Form::jDeleteForm(route('proposal_delete')) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js-files')
    <script>
        $(function(){
            $('.select-container select').multiselect();

            $('.select-container').on('change', 'select', function(){
                window.location = $(this).find('option:selected').data('url');
            });
        });

        function xeditCallback(el, response, newValue)
        {
            if (response.field == 'status_id') {
                el.parents('tr').removeClass('status-' + response.old_value).addClass('status-' + newValue);
            }
        }
    </script>
@stop


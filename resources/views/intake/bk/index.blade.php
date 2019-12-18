@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
        <li><span>Lead</span></li>
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
							<div class="row">
								<div class="col-md-12">
									<div class="portlet light portlet-fit">
										<div style="background:#fff;" class="portlet-title">
											<div class="caption col-xs-12 col-sm-6">
												<span class="caption-subject font-dark bold uppercase">Leads</span>
											</div>

											<div class="text-right col-xs-12 col-sm-6 pl0 ml0 pr0 mr0 mb15 search-container custom-form-search">
												{!! Form::jSearchForm($needle, route('intake_search'), route('intake_list')) !!}
											</div>
										</div>
										<div class="portlet-body">
											<div class="table-scrollable">
												<table class="table table-bordered table-hover">
													<thead>
														<tr>
															<th> First Name</th>
															<th> Last Name</th>
															<th> Email </th>
															<th> Phone </th>
															<th class="text-center"> Detail </th>
														</tr>
													</thead>
													<tbody>
													@if(!empty($lead_data))
													 @foreach ($lead_data as $item)
														<tr>
															<td> {{$item->first_name}} </td>
															<td> {{$item->last_name}} </td>
															<td> {{$item->email}} </td>
															<td> {{$item->phone}} </td>
															<td class="text-center"> 
																<a href="{{ route('intake_detail', ['ID' => $item->id]) }}" class="btn btn-sm btn-circle btn-default btn-editable">
																<i class="fa fa-search"></i> View</a> 
															</td>
														</tr>
														
													 @endforeach
													 @else
														<tr>
															<td class="text-center" colspan="5">Not found lead</td>
														</tr>
													@endif
													</tbody>
												</table>
												<div class="col-xs-12">{!! Form::jPaginator($lead_data, 'intake_list') !!}</div>
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
    </div>
@stop
<style>
.green{
	color:#119b49;
}
tr.detail-row {
    display: none;
}
tr.detail-row.open
{
	display:table-row;
}
.custom-form-search input {
	border-bottom-left-radius: 5px !important;
    border-top-left-radius: 5px !important;
    max-width: 350px;
	height: 38px !important;
}
.custom-form-search button{
	border-bottom-right-radius: 5px !important;
    border-top-right-radius: 5px !important;
    border: none !important;
}
</style>

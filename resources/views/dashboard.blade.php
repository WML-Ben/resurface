@extends('layouts.layout')

@section('breadcrumbs')
    <ul class="page-breadcrumb breadcrumb">
        <li><span>Dashboard</span></li>
    </ul>
@stop

@section('content')
    <div class="page-content-inner">
        <div class="mt-content-body">
		@if(auth()->user()->role_id == 3 || auth()->user()->role_id == 2)
            <div class="row">
                <div class="col-md-12">
                    <div class="row total-boxes">
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-sm-12 col-xs-12">
									<div class="portlet light ">
										<div class="portlet-title">
											<div class="caption caption-md">
												<i class="icon-bar-chart font-dark hide"></i>
												<span class="caption-subject font-green-steel bold uppercase">Reachouts</span>
											</div>
											<div class="actions">
												<div class="btn-group btn-group-devided" data-toggle="buttons">
													<label class="btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm active">
														<input type="radio" name="options" class="toggle" id="option1">This Week</label>
													<label class="btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm">
														<input type="radio" name="options" class="toggle" id="option2">This Month</label>
													<label class="btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm">
														<input type="radio" name="options" class="toggle" id="option2">This Year</label>
												</div>
											</div>
										</div>
										<div class="portlet-body">
											<div class="row number-stats">
												<div class="col-md-6 col-sm-6 col-xs-6 text-center">
													<div class="stat-number">
														<div class="title"> Total </div>
														<div class="number"> 2460 </div>
													</div>
												</div>
												<div class="col-md-6 col-sm-6 col-xs-6">
													<div class="dashboard-stat2" style="margin-bottom: 8px;">
														<div class="progress-info">
															<div class="progress">
																<span style="width: 45%;" class="progress-bar progress-bar-success blue-sharp">
																	<span class="sr-only">45% grow</span>
																</span>
															</div>
															<div class="status">
																<div class="status-title"> grow </div>
																<div class="status-number"> 45% </div>
															</div>
														</div>
													</div>
												</div>										
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-12 col-xs-12">
									<div class="portlet light ">
										<div class="portlet-title">
											<div class="caption caption-md">
												<i class="icon-bar-chart font-dark hide"></i>
												<span class="caption-subject font-green-steel bold uppercase">Follow ups</span>
											</div>
										</div>
										<div class="portlet-body">
											<div class="row number-stats margin-bottom-30">
												<div class="col-md-6 col-sm-6 col-xs-6">
													<div class="stat-left">
														<div class="stat-chart">
															<!-- do not line break "sparkline_bar" div. sparkline chart has an issue when the container div has line break -->
															<div id="sparkline_bar"><canvas width="113" height="55" style="display: inline-block; width: 113px; height: 55px; vertical-align: top;"></canvas></div>
														</div>
														<div class="stat-number">
															<div class="title"> Pending </div>
															<div class="number"> 2460 </div>
														</div>
													</div>
												</div>
												<div class="col-md-6 col-sm-6 col-xs-6">
													<div class="stat-right">
														<div class="stat-chart">
															<!-- do not line break "sparkline_bar" div. sparkline chart has an issue when the container div has line break -->
															<div id="sparkline_bar2"><canvas width="107" height="55" style="display: inline-block; width: 107px; height: 55px; vertical-align: top;"></canvas></div>
														</div>
														<div class="stat-number">
															<div class="title"> Sent </div>
															<div class="number"> 719 </div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
							<div class="portlet light ">
								<div class="portlet-title">
									<div class="caption caption-md">
										<i class="icon-bar-chart font-dark hide"></i>
										<span class="caption-subject font-green-steel bold uppercase">Proposals</span>
									</div>
									<div class="actions">
										<div class="btn-group btn-group-devided" data-toggle="buttons">
											<label class="custom-view-all btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm btn-success">
												View All
											</label>
										</div>
										
									</div>
								</div>
								<div class="portlet-body">
									<div class="scroller" style="max-height:305px;" data-always-visible="1" data-rail-visible="0">
										<div class="row number-stats margin-bottom-30">
											<div class="col-md-6 col-sm-6 col-xs-6">
												<div class="stat-left">
													<div class="stat-chart">
														<div class="sparkline-chart" style="margin-top:0px;">
															<div class="number" id="sparkline_bar6"></div>
														</div>
													</div>
													<div class="stat-number">
														<div class="title"> Pending </div>
														<div class="number"> 240 </div>
													</div>
												</div>
											</div>
											<div class="col-md-6 col-sm-6 col-xs-6">
												<div class="stat-right">
													<div class="stat-chart">
														<div class="sparkline-chart" style="margin-top:0px;">
															<div class="number" id="sparkline_bar5"></div>
                                                        </div>
													</div>
													<div class="stat-number">
														<div class="title"> Active </div>
														<div class="number"> 719 </div>
													</div>
												</div>
											</div>
										</div>
										<div class="table-scrollable table-scrollable-borderless">
											<table class="table table-hover table-light">
												<thead>
													<tr class="uppercase">
														<th> Name of Proposals </th>
														<th> Assigned to </th>
														<th class="text-center"> Age of proposal(day) </th>
														<th> Status </th>
													</tr>
												</thead>
												<tbody>
												<tr>
													<td><span class="primary-link">Castle Group Management</span></td>
													<td> Charlie Blackburn </td>
													<td class="text-center"> <span class="badge badge-success"> 14 </span></td>
													<td>
														<span class="btn btn-xs green"> Active
                                                            
                                                        </span> 
													</td>
												</tr>
												<tr>
													<td><span class="primary-link">Castle Group Management</span></td>
													<td> Wayne Thames </td>
													<td class="text-center"> <span class="badge badge-success"> 10 </span> </td>
													<td>
														<span class="btn btn-xs red"> Rejected
                                                             
                                                        </span> 
													</td>
												</tr>
																						<tr>
													<td><span class="primary-link">Castle Group Management</span></td>
													<td> Wayne Thames </td>
													<td class="text-center"> <span class="badge badge-success"> 30 </span> </td>
													<td>
														<span class="btn btn-xs blue"> Pending
                                                            
                                                        </span> 
													</td>
												</tr>
																						<tr>
													<td><span class="primary-link">Castle Group Management</span></td>
													<td> Charlie Blackburn </td>
													<td class="text-center"> <span class="badge badge-success"> 20 </span> </td>
													<td>
														<span class="btn btn-xs green"> Active
                                                           
                                                        </span> 
													</td>
												</tr>
																						<tr>
													<td><span class="primary-link">Castle Group Management</span></td>
													<td> Charlie Blackburn </td>
													<td class="text-center"> <span class="badge badge-success"> 15 </span> </td>
														<td>
														<span class="btn btn-xs blue"> Pending
                                                           
                                                        </span> 
													</td>
												</tr>
												</tbody>
												</table>
										</div>
									</div>
								</div>
							</div>
						</div>
                    </div>
                </div>
            </div>
			<div class="row">
                <div class="col-md-12">
                    <div class="row total-boxes">
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="portlet light ">
								<div class="portlet-title tabbable-line">
									<div class="caption">
										<i class="icon-globe font-dark hide"></i>
										<span class="caption-subject font-green-steel bold uppercase">Lead </span>
									</div>
									<div class="actions">
										<label class="custom-view-all btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm btn-success">
											<a class="text-none font-white" href="{{route('intake_list')}}">View All</a>
										</label>
									</div>
								</div>
								<div class="portlet-body">
									<div class="scroller" style="height: 210px;" data-always-visible="1" data-rail-visible="0">
										<div class="mt-actions">
											@foreach($activelead as $item)
											<div class="mt-action">
												<div class="mt-action-body">
													<div class="mt-action-row">
														<div class="mt-action-info ">
															<div class="mt-action-icon pull-left">
																<i class="fa fa-bookmark-o"></i>
															</div>
															<div class="mt-action-details pull-left">
																<span class="mt-action-author">{{$item['full_name']}}</span>
																<p class="mt-action-desc">Phone:{{$item['phone']}}</p>
															</div>
															<div class="mt-action-datetime pull-right">
																<span class="mt-action-date">{{$item['meeting_date']}}</span>
																<span class="mt-action-dot bg-green"></span>
																<span class="mt=action-time">{{$item['meeting_time']}}</span>
                                                            </div>
														</div>
													</div>
												</div>
											</div>
											@endforeach
										</div>
									</div>	
									<!--END TABS-->
								</div>
							</div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="portlet light ">
								<div class="portlet-title">
									<div class="caption caption-md">
										<i class="icon-bar-chart font-dark hide"></i>
										<span class="caption-subject font-green-steel bold uppercase">Permitting</span>
									</div>
									<div class="actions">
										<label class="custom-view-all btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm btn-success">
											View All
										</label>
									</div>
								</div>
								<div class="portlet-body">
								<div class="scroller" style="height: 210px;" data-always-visible="1" data-rail-visible="0">
									<ul class="feeds">
                                            <li>
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col1">
                                                            <div class="label label-sm label-info">
                                                                <i class="fa fa-check"></i>
                                                            </div>
                                                        </div>
                                                        <div class="cont-col2">
                                                            <div class="desc"> This is permitting 1 
																<span class="label label-sm label-info"> Approved
                                                                    <i class="fa fa-share"></i>
                                                                 </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col2">
                                                    <div class="date"> Just now </div>
                                                </div>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <div class="col1">
                                                        <div class="cont">
                                                            <div class="cont-col1">
																<div class="label label-sm label-danger">
																	<i class="fa fa-bolt"></i>
																</div>
                                                            </div>
                                                            <div class="cont-col2">
                                                                <div class="desc"> This is permitting 2 <span class="label label-sm label-default "> Not Submittted </span></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col2">
                                                        <div class="date"> 20 mins </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col1">
                                                            <div class="label label-sm label-info">
																	<i class="fa fa-bullhorn"></i>
															</div>
                                                        </div>
                                                        <div class="cont-col2">
                                                            <div class="desc"> This is permitting 3 <span class="label label-sm label-info"> Approved
                                                                    <i class="fa fa-share"></i>
                                                                 </span></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col2">
                                                    <div class="date"> 24 mins </div>
                                                </div>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <div class="col1">
                                                        <div class="cont">
                                                            <div class="cont-col1">
                                                                <div class="label label-sm label-info">
																	<i class="fa fa-check"></i>
																</div>
                                                            </div>
                                                            <div class="cont-col2">
                                                                <div class="desc"> This is permitting 2. Lorem is push <span class="label label-sm label-info"> Approved
                                                                    <i class="fa fa-share"></i>
                                                                 </span></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col2">
                                                        <div class="date"> 20 mins </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col1">
                                                            <div class="label label-sm label-info">
																	<i class="fa fa-bullhorn"></i>
															</div>
                                                        </div>
                                                        <div class="cont-col2">
                                                            <div class="desc"> This is permitting 10. Lorem is... <span class="label label-sm label-success"> Completed
                                                                    <i class="fa fa-share"></i>
                                                                 </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col2">
                                                    <div class="date"> Just now </div>
                                                </div>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <div class="col1">
                                                        <div class="cont">
                                                            <div class="cont-col1">
                                                                <div class="label label-sm label-info">
																	<i class="fa fa-check"></i>
																</div>
                                                            </div>
                                                            <div class="cont-col2">
                                                                <div class="desc"> This is follow ups 6. </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col2">
                                                        <div class="date"> 20 mins </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col1">
                                                            <div class="label label-sm label-info">
																	<i class="fa fa-check"></i>
															</div>
                                                        </div>
                                                        <div class="cont-col2">
                                                            <div class="desc"> This is follow ups 7 </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col2">
                                                    <div class="date"> 24 mins </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col1">
                                                            <div class="label label-sm label-info">
																	<i class="fa fa-check"></i>
															</div>
                                                        </div>
                                                        <div class="cont-col2">
                                                            <div class="desc"> This is follow ups 8
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col2">
                                                    <div class="date"> 30 mins </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col1">
                                                            <div class="label label-sm label-info">
																	<i class="fa fa-check"></i>
															</div>
                                                        </div>
                                                        <div class="cont-col2">
                                                            <div class="desc">This is follow ups 9 </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col2">
                                                    <div class="date"> 24 mins </div>
                                                </div>
                                            </li>
                                        </ul>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="portlet light ">
								<div class="portlet-title">
									<div class="caption caption-md">
										<i class="icon-bar-chart font-dark hide"></i>
										<span class="caption-subject font-green-steel bold uppercase">Phasing </span>
									</div>
									<div class="actions">
										<label class="custom-view-all btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm btn-success">
											View All
										</label>
									</div>
								</div>
								<div class="portlet-body">
								<div class="scroller" style="height: 210px;" data-always-visible="1" data-rail-visible="0">
									<ul class="feeds">
                                            <li>
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col1">
                                                            <div class="label label-sm label-success">
                                                                <i class="fa fa-check"></i>
                                                            </div>
                                                        </div>
                                                        <div class="cont-col2">
                                                            <div class="desc"> This is task 1 
																<span class="label label-sm label-success">
																	Completed
                                                                    <i class="fa fa-share"></i>
                                                                 </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col2">
                                                    <div class="date"> Just now </div>
                                                </div>
                                            </li>
											<li>
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col1">
                                                            <div class="label label-sm label-danger">
                                                                <i class="fa fa-check"></i>
                                                            </div>
                                                        </div>
                                                        <div class="cont-col2">
                                                            <div class="desc"> This is task 2 
																<span class="label label-sm label-danger">
																	Uncomplete
                                                                    <i class="fa fa-share"></i>
                                                                 </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col2">
                                                    <div class="date"> 02/02/2019 </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col1">
                                                            <div class="label label-sm label-info">
                                                                <i class="fa fa-share"></i>
                                                            </div>
                                                        </div>
                                                        <div class="cont-col2">
                                                            <div class="desc"> This is task 3 
																<span class="label label-sm label-info">
																	Pedding
                                                                    <i class="fa fa-share"></i>
                                                                 </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col2">
                                                    <div class="date"> 02/03/2019 </div>
                                                </div>
                                            </li>
											 <li>
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col1">
                                                            <div class="label label-sm label-success">
                                                                <i class="fa fa-check"></i>
                                                            </div>
                                                        </div>
                                                        <div class="cont-col2">
                                                            <div class="desc"> This is task 1 
																<span class="label label-sm label-success">
																	Completed
                                                                    <i class="fa fa-share"></i>
                                                                 </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col2">
                                                    <div class="date"> Just now </div>
                                                </div>
                                            </li>
											<li>
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col1">
                                                            <div class="label label-sm label-danger">
                                                                <i class="fa fa-check"></i>
                                                            </div>
                                                        </div>
                                                        <div class="cont-col2">
                                                            <div class="desc"> This is task 2 
																<span class="label label-sm label-danger">
																	Uncomplete
                                                                    <i class="fa fa-share"></i>
                                                                 </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col2">
                                                    <div class="date"> 02/02/2019 </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col1">
                                                            <div class="label label-sm label-info">
                                                                <i class="fa fa-share"></i>
                                                            </div>
                                                        </div>
                                                        <div class="cont-col2">
                                                            <div class="desc"> This is task 3 
																<span class="label label-sm label-info">
																	Pedding
                                                                    <i class="fa fa-share"></i>
                                                                 </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col2">
                                                    <div class="date"> 02/03/2019 </div>
                                                </div>
                                            </li>
                                        </ul>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row widget-row">
				<div class="col-md-4 col-sm-4 col-xs-12">
					<!-- BEGIN WIDGET THUMB -->
					<div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
						<h4 class="widget-thumb-heading">Scheduled </h4>
						<div class="widget-thumb-wrap">
							<i class="widget-thumb-icon bg-green fa fa-bookmark-o"></i>
							<div class="widget-thumb-body">
								<span class="widget-thumb-subtitle">Total</span>
								<span class="widget-thumb-body-stat" data-counter="counterup" data-value="7,644">0</span>
							</div>
						</div>
					</div>
					<!-- END WIDGET THUMB -->
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12">
					<!-- BEGIN WIDGET THUMB -->
					<div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
						<h4 class="widget-thumb-heading">Need to schedule</h4>
						<div class="widget-thumb-wrap">
							<i class="widget-thumb-icon bg-red icon-layers"></i>
							<div class="widget-thumb-body">
								<span class="widget-thumb-subtitle">Total</span>
								<span class="widget-thumb-body-stat" data-counter="counterup" data-value="7,644">0</span>
							</div>
						</div>
					</div>
					<!-- END WIDGET THUMB -->
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12">
					<!-- BEGIN WIDGET THUMB -->
					<div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
						<h4 class="widget-thumb-heading">Completed </h4>
						<div class="widget-thumb-wrap">
							<i class="widget-thumb-icon bg-purple fa fa-check"></i>
							<div class="widget-thumb-body">
								<span class="widget-thumb-subtitle">Total</span>
								<span class="widget-thumb-body-stat" data-counter="counterup" data-value="7,644">0</span>
							</div>
						</div>
					</div>
					<!-- END WIDGET THUMB -->
				</div>
			</div>	
			<div class="row widget-row">
				<div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
					<div class="portlet light ">
						<div class="portlet-title">
							<div class="caption caption-md">
								<i class="icon-bar-chart font-dark hide"></i>
								<span class="caption-subject font-green-steel bold uppercase">Billing</span>
							</div>
							<div class="actions">
								<div class="btn-group btn-group-devided" data-toggle="buttons">
									<label class="btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm"><span class="md-click-circle md-click-animate" style="height: 66px; width: 66px; top: -13.4px; left: 4.48749px;"></span>
										<input type="radio" name="options" class="toggle" id="option1">Deposit</label>
									<label class="btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm active">
										<input type="radio" name="options" class="toggle" id="option2">Progressive </label>
									<label class="btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm">
										<input type="radio" name="options" class="toggle" id="option2">Final</label>
									
								</div>
								
							</div>
						</div>
						<div class="portlet-body">
						<div class="scroller" style="height: 284px;" data-always-visible="1" data-rail-visible="0">
							<div class="table-scrollable table-scrollable-borderless">
								<table class="table table-hover table-light">
									<thead>
										<tr class="uppercase">
											<th> Name </th>
											<th> Assigned to </th>
											<th> Create Bill </th>
											<th> Status </th>
										</tr>
									</thead>
									<tbody>
									<tr>
										<td><a href="javascript:;" class="primary-link">This is invoices 1</a></td>
										<td> Alexandra Test </td>
										<td> 03/12/2018 9:30 </td>
										<td><span class="label label-sm label-danger">
																	Ready Billing
                                                                    <i class="fa fa-share"></i>
                                                    </span> </td>
									</tr>
																														<tr>
										<td><a href="javascript:;" class="primary-link">This is invoices 2</a></td>
										<td> Alexandra Test </td>
										<td> 03/12/2018 9:30 </td>
										<td><span class="label label-sm label-success">
																	Billed
                                                                    <i class="fa fa-share"></i>
                                                    </span> </td>
									</tr>
																			<tr>
										<td><a href="javascript:;" class="primary-link">This is invoices 3</a></td>
										<td> Alexandra Test </td>
										<td> 03/12/2018 9:30 </td>
										<td> <span class="label label-sm label-danger">
																	Ready Billing
                                                                    <i class="fa fa-share"></i>
                                                    </span></td>
									</tr>
																			<tr>
										<td><a href="javascript:;" class="primary-link">This is invoices 4</a></td>
										<td> Alexandra Test </td>
										<td> 03/12/2018 9:30 </td>
										<td> <span class="label label-sm label-danger">
																	Ready Billing
                                                                    <i class="fa fa-share"></i>
                                                    </span></td>
									</tr>
																			<tr>
										<td><a href="javascript:;" class="primary-link">This is invoices 5</a></td>
										<td> Alexandra Test </td>
										<td> 03/12/2018 9:30 </td>
										<td> <span class="label label-sm label-success">
																	Billed
                                                                    <i class="fa fa-share"></i>
                                                    </span>  </td>
									</tr>
									</tbody>
									</table>
							</div></div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-sm-12 col-xs-12"> 
									<div class="portlet light ">
										<div class="dashboard-stat2 ">
											<div class="display">
												<div class="number">
													<h3 class="font-blue-sharp">
														<span data-counter="counterup" data-value="567">567</span>
													</h3>
													<small>Expenses  </small>
												</div>
												<div class="icon">
													<i class="icon-basket"></i>
												</div>
											</div>
											<div class="progress-info">
												<div class="progress">
													<span style="width: 45%;" class="progress-bar progress-bar-success blue-sharp">
														<span class="sr-only">45% grow</span>
													</span>
												</div>
												<div class="status">
													<div class="status-title"> grow </div>
													<div class="status-number"> 45% </div>
												</div>
											</div>
										</div>
										
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-xs-12"> 
									<div class="portlet light ">
										<div class="dashboard-stat2 ">
											<div class="display">
												<div class="number">
													<h3 class="font-blue-sharp">
														<span data-counter="counterup" data-value="567">56</span>
													</h3>
													<small>Sales  </small>
												</div>
												<div class="icon">
													<i class="icon-basket"></i>
												</div>
											</div>
											<div class="progress-info">
												<div class="progress">
													<span style="width: 70%;" class="progress-bar progress-bar-success blue-sharp">
														<span class="sr-only">70% grow</span>
													</span>
												</div>
												<div class="status">
													<div class="status-title"> grow </div>
													<div class="status-number"> 70% </div>
												</div>
											</div>
										</div>
										
									</div>
								</div>
							</div>
					</div>
			</div>	
			@endif
			@if (auth()->user()->role_id == 7 || auth()->user()->role_id == 8)
            <div class="row">
                <div class="col-md-12">
                    @include('errors._list')
                    <div class="row total-boxes">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="portlet light ">
								<div class="portlet-title tabbable-line">
									<div class="caption">
										<i class="icon-globe font-dark hide"></i>
										<span class="caption-subject font-green-steel bold uppercase">Reachouts</span>
									</div>
									<div class="actions">
										<label class="custom-view-all btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm btn-success">
											View All
										</label>
									</div>
								</div>
								<div class="portlet-body">
									<div class="scroller" style="height: 210px;" data-always-visible="1" data-rail-visible="0">
										<div class="mt-actions">
											<div class="mt-action">
												<div class="mt-action-body">
													<div class="mt-action-row">
														<div class="mt-action-info ">
															<div class="mt-action-icon ">
																<i class="fa fa-comments"></i>
															</div>
															<div class="mt-action-details ">
																<span class="mt-action-author">test@gmail.com</span>
																<p class="mt-action-desc">Dummy text of the printing</p>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="mt-action">
												<div class="mt-action-body">
													<div class="mt-action-row">
														<div class="mt-action-info ">
															<div class="mt-action-icon ">
																<i class="fa fa-comments"></i>
															</div>
															<div class="mt-action-details ">
																<span class="mt-action-author">Gavin Bond</span>
																<p class="mt-action-desc">pending for approval</p>
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="mt-action">
												<div class="mt-action-body">
													<div class="mt-action-row">
														<div class="mt-action-info ">
															<div class="mt-action-icon ">
																<i class="fa fa-comments"></i>
															</div>
															<div class="mt-action-details ">
																<span class="mt-action-author">Diana Berri</span>
																<p class="mt-action-desc">Lorem Ipsum is simply dummy text</p>
															</div>
														</div>
														
													</div>
												</div>
											</div>
											<div class="mt-action">
												<div class="mt-action-body">
													<div class="mt-action-row">
														<div class="mt-action-info ">
															<div class="mt-action-icon ">
																<i class=" fa fa-comments"></i>
															</div>
															<div class="mt-action-details ">
																<span class="mt-action-author">John Clark</span>
																<p class="mt-action-desc">Text of the printing and typesetting industry</p>
															</div>
														</div>
														
													</div>
												</div>
											</div>
											<div class="mt-action">
												<div class="mt-action-body">
													<div class="mt-action-row">
														<div class="mt-action-info ">
															<div class="mt-action-icon ">
																<i class="fa fa-comments"></i>
															</div>
															<div class="mt-action-details ">
																<span class="mt-action-author">Donna Clarkson </span>
																<p class="mt-action-desc">Simply dummy text of the printing</p>
															</div>
														</div>
														
													</div>
												</div>
											</div>
											<div class="mt-action">
												<div class="mt-action-body">
													<div class="mt-action-row">
														<div class="mt-action-info ">
															<div class="mt-action-icon ">
																<i class="icon-magnet"></i>
															</div>
															<div class="mt-action-details ">
																<span class="mt-action-author">Tom Larson</span>
																<p class="mt-action-desc">Lorem Ipsum is simply dummy text</p>
															</div>
														</div>
														
													</div>
												</div>
											</div>
										</div>
									</div>
									<!--END TABS-->
								</div>
							</div>
                        </div>
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="portlet light ">
								<div class="portlet-title tabbable-line">
									<div class="caption">
										<i class="icon-globe font-dark hide"></i>
										<span class="caption-subject font-green-steel bold uppercase">Proposals </span>
									</div>
									<div class="actions">
										<label class="custom-view-all btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm btn-success">
											View All
										</label>
									</div>
								</div>
								<div class="portlet-body">
								<div class="scroller" style="height: 210px;" data-always-visible="1" data-rail-visible="0">
									<div class="mt-actions">
											<div class="mt-action">
												<div class="mt-action-body">
													<div class="mt-action-row">
														<div class="mt-action-info ">
															<div class="mt-action-icon ">
																<i class="fa fa-bookmark-o"></i>
															</div>
															<div class="mt-action-details ">
																<span class="mt-action-author">Proposals AllPaving</span>
																<p class="mt-action-desc">Customer: Jon Will</p>
															</div>
															<div class="mt-action-datetime ">
																<span class="mt-action-date">3 jun</span>
																<span class="mt-action-dot bg-green"></span>
																<span class="mt=action-time">9:30-13:00</span>
                                                            </div>
														</div>
													</div>
												</div>
											</div>
											<div class="mt-action">
												<div class="mt-action-body">
													<div class="mt-action-row">
														<div class="mt-action-info ">
															<div class="mt-action-icon ">
																<i class="fa fa-bookmark-o"></i>
															</div>
															<div class="mt-action-details ">
																<span class="mt-action-author">Proposals AllPaving</span>
																<p class="mt-action-desc">Customer: Jon Will</p>
															</div>
															<div class="mt-action-datetime ">
																<span class="mt-action-date">3 jun</span>
																<span class="mt-action-dot bg-green"></span>
																<span class="mt=action-time">9:30-13:00</span>
                                                            </div>
														</div>
													</div>
												</div>
											</div>
											<div class="mt-action">
												<div class="mt-action-body">
													<div class="mt-action-row">
														<div class="mt-action-info ">
															<div class="mt-action-icon ">
																<i class="fa fa-bookmark-o"></i>
															</div>
															<div class="mt-action-details ">
																<span class="mt-action-author">Proposals AllPaving</span>
																<p class="mt-action-desc">Customer: Jon Will</p>
															</div>
															<div class="mt-action-datetime ">
																<span class="mt-action-date">3 jun</span>
																<span class="mt-action-dot bg-green"></span>
																<span class="mt=action-time">9:30-13:00</span>
                                                            </div>
														</div>
														
													</div>
												</div>
											</div>
											<div class="mt-action">
												<div class="mt-action-body">
													<div class="mt-action-row">
														<div class="mt-action-info ">
															<div class="mt-action-icon ">
																<i class="fa fa-bookmark-o"></i>
															</div>
															<div class="mt-action-details ">
																<span class="mt-action-author">Proposals AllPaving</span>
																<p class="mt-action-desc">Customer: Jon Will</p>
															</div>
															<div class="mt-action-datetime ">
																<span class="mt-action-date">3 jun</span>
																<span class="mt-action-dot bg-green"></span>
																<span class="mt=action-time">9:30-13:00</span>
                                                            </div>
														</div>
														
													</div>
												</div>
											</div>
											<div class="mt-action">
												<div class="mt-action-body">
													<div class="mt-action-row">
														<div class="mt-action-info ">
															<div class="mt-action-icon ">
																<i class="fa fa-bookmark-o"></i>
															</div>
															<div class="mt-action-details ">
																<span class="mt-action-author">Proposals AllPaving</span>
																<p class="mt-action-desc">Customer: Jon Will</p>
															</div>
															<div class="mt-action-datetime ">
																<span class="mt-action-date">3 jun</span>
																<span class="mt-action-dot bg-green"></span>
																<span class="mt=action-time">9:30-13:00</span>
                                                            </div>
														</div>
														
													</div>
												</div>
											</div>
											<div class="mt-action">
												<div class="mt-action-body">
													<div class="mt-action-row">
														<div class="mt-action-info ">
															<div class="mt-action-icon ">
																<i class="fa fa-bookmark-o"></i>
															</div>
															<div class="mt-action-details ">
																<span class="mt-action-author">Proposals AllPaving</span>
																<p class="mt-action-desc">Customer: Jon Will</p>
															</div>
															<div class="mt-action-datetime ">
																<span class="mt-action-date">3 jun</span>
																<span class="mt-action-dot bg-green"></span>
																<span class="mt=action-time">9:30-13:00</span>
                                                            </div>
														</div>
														
													</div>
												</div>
											</div>
										</div>
									</div>	
									<!--END TABS-->
								</div>
							</div>
                        </div>
						<div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
							<div class="portlet light ">
								<div class="portlet-title">
									<div class="caption caption-md">
										<i class="icon-bar-chart font-dark hide"></i>
										<span class="caption-subject font-green-steel bold uppercase">Follow ups</span>
									</div>
									<div class="actions">
										<div class="btn-group btn-group-devided" data-toggle="buttons">
											<label class="btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm active">
												<input type="radio" name="options" class="toggle" id="option2">This Week</label>
										</div>
										<label class="custom-view-all btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm btn-success">
											View All
										</label>
									</div>
								</div>
								<div class="portlet-body">
								<div class="scroller" style="height: 210px;" data-always-visible="1" data-rail-visible="0">
									<ul class="feeds">
                                            <li>
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col1">
                                                            <div class="label label-sm label-info">
                                                                <i class="fa fa-check"></i>
                                                            </div>
                                                        </div>
                                                        <div class="cont-col2">
                                                            <div class="desc"> This is follow ups 1
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col2">
                                                    <div class="date"> Just now </div>
                                                </div>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <div class="col1">
                                                        <div class="cont">
                                                            <div class="cont-col1">
																<div class="label label-sm label-info">
																	<i class="fa fa-check"></i>
																</div>
                                                            </div>
                                                            <div class="cont-col2">
                                                                <div class="desc"> This is follow ups 2 </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col2">
                                                        <div class="date"> 20 mins </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col1">
                                                            <div class="label label-sm label-info">
																	<i class="fa fa-check"></i>
															</div>
                                                        </div>
                                                        <div class="cont-col2">
                                                            <div class="desc"> This is follow ups 3 </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col2">
                                                    <div class="date"> 24 mins </div>
                                                </div>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <div class="col1">
                                                        <div class="cont">
                                                            <div class="cont-col1">
                                                                <div class="label label-sm label-info">
																	<i class="fa fa-check"></i>
																</div>
                                                            </div>
                                                            <div class="cont-col2">
                                                                <div class="desc"> This is follow ups 4 </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col2">
                                                        <div class="date"> 20 mins </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col1">
                                                            <div class="label label-sm label-info">
																	<i class="fa fa-check"></i>
															</div>
                                                        </div>
                                                        <div class="cont-col2">
                                                            <div class="desc"> This is follow ups 5
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col2">
                                                    <div class="date"> Just now </div>
                                                </div>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <div class="col1">
                                                        <div class="cont">
                                                            <div class="cont-col1">
                                                                <div class="label label-sm label-info">
																	<i class="fa fa-check"></i>
																</div>
                                                            </div>
                                                            <div class="cont-col2">
                                                                <div class="desc"> This is follow ups 6. </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col2">
                                                        <div class="date"> 20 mins </div>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col1">
                                                            <div class="label label-sm label-info">
																	<i class="fa fa-check"></i>
															</div>
                                                        </div>
                                                        <div class="cont-col2">
                                                            <div class="desc"> This is follow ups 7 </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col2">
                                                    <div class="date"> 24 mins </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col1">
                                                            <div class="label label-sm label-info">
																	<i class="fa fa-check"></i>
															</div>
                                                        </div>
                                                        <div class="cont-col2">
                                                            <div class="desc"> This is follow ups 8
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col2">
                                                    <div class="date"> 30 mins </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="col1">
                                                    <div class="cont">
                                                        <div class="cont-col1">
                                                            <div class="label label-sm label-info">
																	<i class="fa fa-check"></i>
															</div>
                                                        </div>
                                                        <div class="cont-col2">
                                                            <div class="desc">This is follow ups 9 </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col2">
                                                    <div class="date"> 24 mins </div>
                                                </div>
                                            </li>
                                        </ul>
									</div>
								</div>
							</div>
						</div>
                    </div>
					<div class="row">
						<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
							<div class="dashboard-stat2 ">
								<div class="display">
									<div class="number">
										<h3 class="font-green-sharp">
											<span data-counter="counterup" data-value="7800">7800</span>
											<small class="font-green-sharp"></small>
										</h3>
										<small>TOTAL SALES</small>
									</div>
									<div class="icon">
										<i class="icon-pie-chart"></i>
									</div>
								</div>
								<div class="progress-info">
									<div class="progress">
										<span style="width: 76%;" class="progress-bar progress-bar-success green-sharp">
											<span class="sr-only">76% progress</span>
										</span>
									</div>
									<div class="status">
										<div class="status-title"> progress </div>
										<div class="status-number"> 76% </div>
									</div>
								</div>
							</div>
							<div class="dashboard-stat2 ">
								<div class="display">
									<div class="number">
										<h3 class="font-green-sharp">
											<span data-counter="counterup" data-value="780">780</span>
											<small class="font-green-sharp"></small>
										</h3>
										<small>TOTAL LOREMS</small>
									</div>
									<div class="icon">
										<i class="icon-pie-chart"></i>
									</div>
								</div>
								<div class="progress-info">
									<div class="progress">
										<span style="width: 60%;" class="progress-bar progress-bar-success green-sharp">
											<span class="sr-only">60% progress</span>
										</span>
									</div>
									<div class="status">
										<div class="status-title"> progress </div>
										<div class="status-number"> 60% </div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
							<div class="portlet light ">
								<div class="portlet-title">
									<div class="caption caption-md">
										<i class="icon-bar-chart font-dark hide"></i>
										<span class="caption-subject font-green-steel bold uppercase">Lead</span>
									</div>
									<div class="actions">
										<div class="btn-group btn-group-devided" data-toggle="buttons">
											<label class="btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm"><span class="md-click-circle md-click-animate" style="height: 66px; width: 66px; top: -13.4px; left: 4.48749px;"></span>
												<input type="radio" name="options" class="toggle" id="option1">Today</label>
											<label class="btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm active">
												<input type="radio" name="options" class="toggle" id="option2">Week</label>
											<label class="btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm">
												<input type="radio" name="options" class="toggle" id="option2">Month</label>
											<label class="custom-view-all btn btn-transparent blue-oleo btn-no-border btn-outline btn-circle btn-sm btn-success">
												View All
											</label>
										</div>
										
									</div>
								</div>
								<div class="portlet-body">
								<div class="scroller" style="height: 185px;" data-always-visible="1" data-rail-visible="0">
									<div class="row number-stats margin-bottom-30">
										<div class="col-md-6 col-sm-6 col-xs-6">
											<div class="stat-left">
												<div class="stat-chart">
													<!-- do not line break "sparkline_bar" div. sparkline chart has an issue when the container div has line break -->
													<div id="sparkline_bar"><canvas width="113" height="55" style="display: inline-block; width: 113px; height: 55px; vertical-align: top;"></canvas></div>
												</div>
												<div class="stat-number">
													<div class="title"> Total </div>
													<div class="number"> 2460 </div>
												</div>
											</div>
										</div>
										<div class="col-md-6 col-sm-6 col-xs-6">
											<div class="stat-right">
												<div class="stat-chart">
													<!-- do not line break "sparkline_bar" div. sparkline chart has an issue when the container div has line break -->
													<div id="sparkline_bar2"><canvas width="107" height="55" style="display: inline-block; width: 107px; height: 55px; vertical-align: top;"></canvas></div>
												</div>
												<div class="stat-number">
													<div class="title"> New </div>
													<div class="number"> 719 </div>
												</div>
											</div>
										</div>
									</div>
									<div class="table-scrollable table-scrollable-borderless">
										<table class="table table-hover table-light">
											<thead>
												<tr class="uppercase">
													<th> Email </th>
													<th> Assigned to </th>
													<th> Meeting </th>
													<th> Intake Date </th>
												</tr>
											</thead>
											<tbody>
											<tr>
												<td><a href="javascript:;" class="primary-link">test@gmail.com</a></td>
												<td> Alexandra Test </td>
												<td> 03/12/2018 9:30 </td>
												<td> 01/12/2018 </td>
											</tr>
																																<tr>
												<td><a href="javascript:;" class="primary-link">test@gmail.com</a></td>
												<td> Alexandra Test </td>
												<td> 03/12/2018 9:30 </td>
												<td> 01/12/2018 </td>
											</tr>
																					<tr>
												<td><a href="javascript:;" class="primary-link">test@gmail.com</a></td>
												<td> Alexandra Test </td>
												<td> 03/12/2018 9:30 </td>
												<td> 01/12/2018 </td>
											</tr>
																					<tr>
												<td><a href="javascript:;" class="primary-link">test@gmail.com</a></td>
												<td> Alexandra Test </td>
												<td> 03/12/2018 9:30 </td>
												<td> 01/12/2018 </td>
											</tr>
																					<tr>
												<td><a href="javascript:;" class="primary-link">test@gmail.com</a></td>
												<td> Alexandra Test </td>
												<td> 03/12/2018 9:30 </td>
												<td> 01/12/2018 </td>
											</tr>
											</tbody>
											</table>
									</div></div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
							<div class="portlet light ">
								<div class="dashboard-stat2 ">
									<div class="display">
										<div class="number">
											<h3 class="font-blue-sharp">
												<span data-counter="counterup" data-value="567">567</span>
											</h3>
											<small>Proposals </small>
										</div>
										<div class="icon">
											<i class="icon-basket"></i>
										</div>
									</div>
									<div class="progress-info">
										<div class="progress">
											<span style="width: 45%;" class="progress-bar progress-bar-success blue-sharp">
												<span class="sr-only">45% grow</span>
											</span>
										</div>
										<div class="status">
											<div class="status-title"> grow </div>
											<div class="status-number"> 45% </div>
										</div>
									</div>
								</div>	
							</div>
						</div>
					</div>
                </div>
            </div>
			@endif
        </div>
    </div>
@stop

@section('css-page-level-plugins')
    {!! Html::style($publicUrl .'/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') !!}
    {!! Html::style($publicUrl .'/assets/global/plugins/morris/morris.css') !!}
    {!! Html::style($publicUrl .'/assets/global/plugins/fullcalendar/fullcalendar.min.css') !!}
    {!! Html::style($publicUrl .'/assets/global/plugins/jqvmap/jqvmap/jqvmap.css') !!}
@stop

@section('js-page-level-plugins')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    {!! Html::script($publicUrl .'/assets/global/plugins/moment.min.js') !!}
    {!! Html::script($publicUrl .'/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') !!}
    {!! Html::script($publicUrl .'/assets/global/plugins/morris/morris.min.js') !!}
    {!! Html::script($publicUrl .'/assets/global/plugins/morris/raphael-min.js') !!}
@stop

@section('js-page-level-scripts')
    {!! Html::script($publicUrl .'/assets/pages/scripts/dashboard.min.js') !!}
@stop
<style>
.sparkline-chart .number{
	width:130px !important;
}
</style>
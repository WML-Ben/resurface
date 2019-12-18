<div class="page-wrapper-row">
    <div class="page-wrapper-top">
        <!-- BEGIN HEADER -->
		<div class="page-header navbar navbar-fixed-top">
        <!-- BEGIN HEADER INNER -->
        <div class="page-header-inner ">
            <!-- BEGIN LOGO -->
            <div class="page-logo">
                <a href="{{ route('dashboard') }}">
                    <img src="{{ $publicUrl }}/assets/img/allPavingLogo.svg" alt="logo" class="logo-default" width="100px"> </a>
            </div>

            @include('layouts._nav_main')

            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="icon-bell"></i>
                            <span class="badge badge-default"> 0 </span>
                        </a>
						 @include('layouts._notifications')
                    </li>

                    <li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="fa fa-envelope-o"></i>
                            <span class="badge badge-default"> {{ $myUnreadMessages['total'] }} </span>
                        </a>
						@include('layouts._messages')
                    </li>

                    <li class="dropdown dropdown-extended dropdown-tasks" id="header_task_bar">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                            <i class="icon-calendar"></i>
                            <span class="badge badge-default"> {{ $myIncompleteTasks['total'] }} </span>
                        </a>
						 @include('layouts._tasks')
                    </li>

                    <li class="dropdown dropdown-user">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
							@if (!empty(auth()->user()->avatar))
								<img src="{{ $mediaUrl.'/avatars/'.auth()->user()->avatar }}" alt="{{ auth()->user()->avatar }}" class="mw40 br64 mr5">
							@else
								<img src="{{ $publicUrl.'/images/'.$config['defaultAvatar'] }}" alt="default avatar" class="mw40 br64 mr10">
							@endif
                            <span class="username username-hide-on-mobile"> {{ auth()->user()->fullName }} </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
							<li>
									<a href="#">
										<i class="icon-user"></i> My Profile </a>
							</li>
                            <li>
                                <a href="javascript:void(0);">
                                    <i class="fa fa-envelope-o"></i> My Inbox
                                    <span class="badge badge-danger"> {{ $myUnreadMessages['total'] }} </span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <i class="icon-rocket"></i> My Tasks
                                    <span class="badge badge-success"> {{ $myIncompleteTasks['total'] }} </span>
                                </a>
                            </li>
                            <li class="divider"> </li>
                            <li>
                                <a href="{{ route('logout') }}">
                                    <i class="icon-key"></i> Log Out </a>
                            </li>
                        </ul>
                    </li>
                    <!-- END USER LOGIN DROPDOWN -->
                </ul>
            </div>
            <!-- END TOP NAVIGATION MENU -->
        </div>
        <!-- END HEADER INNER -->
    </div>
    </div>
</div>
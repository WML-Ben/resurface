<div class="hor-menu  ">
    <ul class="nav navbar-nav">
        {{--
        <li class="{{ ($action['actionController'] == 'main' && $action['actionFunction'] == 'dashboard') ? ' active' : '' }}">
            <a href="{{ route('dashboard') }}">Dashboard<span class="arrow"></span></a>
        </li>
        --}}

        <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown">
            <a href="javascript:;">CRM <i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu pull-left">
                <li><a href="{{ route('property_list') }}">Properties</a></li>
                <li><a href="{{ route('company_list') }}">Companies</a></li>
                <li><a href="{{ route('contact_list') }}" class="">Contacts</a></li>
				<li><a href="{{ route('intake_list') }}" class="">Leads</a></li>
                @if (auth()->user()->hasPrivilege('list-employee'))
                    <li><a href="{{ route('employee_list') }}">AP Employees</a></li>
                @endif
                @if (auth()->user()->hasPrivilege('list-client'))
                    <li><a href="{{ route('client_list') }}">Clients</a></li>
                @endif
            </ul>
        </li>
        {{--
        <li class="{{ $action['actionController'] == 'proposals' ? ' active' : '' }}">
            <a href="{{ route('proposal_list') }}">Proposals<span class=""></span></a>
        </li>

        {{--
        <li class="{{ $action['actionController'] == 'workorders' ? ' active' : '' }}">
            <a href="{{ route('work_order_list') }}">Work Orders<span class=""></span></a>
        </li>
        --}}

        <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown">
            <a href="javascript:;">Proposals <i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu pull-left">
			    <li><a href="#">All Proposals</a></li>
                <li><a href="#">New Proposal</a></li>
				<li><a href="{{ route('proposal_draft_list') }}">Draft</a></li>
            </ul>
        </li>

        <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown">
            <a href="javascript:;">Work Orders <i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu pull-left">
                <li><a href="{{ route('work_order_processing_list') }}">Processing</a></li>
                <li><a href="{{ route('work_order_active_list') }}">Active</a></li>
                <li><a href="{{ route('work_order_billing_list') }}" class="">Billing</a></li>
            </ul>
        </li>

        <li class="{{ $action['actionController'] == 'orderservices' ? ' active' : '' }}">
            <a href="{{ route('order_service_list') }}">Services<span class=""></span></a>
        </li>

        {{--
        <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown">
            <a href="javascript:;">Proposals<span class="arrow"></span></a>
            <ul class="dropdown-menu pull-left">
                <li><a href="{{ route('proposal_list') }}"> Proposals </a></li>
                <li><a href="#" class="not-yet-available">Create Email Template</a></li>
            </ul>
        </li>
        <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown">
            <a href="javascript:;">Work Orders<span class="arrow"></span></a>
            <ul class="dropdown-menu pull-left">
                <li><a href="#" class="not-yet-available">Work Orders</a></li>
                <li><a href="#" class="not-yet-available">Services</a></li>
            </ul>
        </li>
        --}}

        <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown">
            <a href="javascript:;">Calendar <i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu pull-left">
                <li><a href="{{ route('appointment_list') }}" class="">Appointments</a></li>
                <li><a href="{{ route('task_list') }}" class="">Tasks</a></li>
            </ul>
        </li>
        <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown">
            <a href="javascript:;">Resources <i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu pull-left">
                <li><a href="{{ route('vehicle_list') }}" class="">Company Vehicles</a></li>
                <li><a href="{{ route('vehicle_type_list') }}" class="">Vehicle Types</a></li>
                <li><a href="{{ route('vehicle_log_type_list') }}" class="">Vehicle Log Types</a></li>
                <li><a href="{{ route('materials_list') }}" class="">Materials/Rates</a></li>
                <li><a href="{{ route('equipments_list') }}" class="">Equipment/Rates</a></li>
                <li><a href="{{ route('labor_list') }}" class="">Labor Rates</a></li>
                <li><a href="{{ route('location_list') }}" class="">Company Locations</a></li>
                <li><a href="#" class="not-yet-available">Upload Types</a></li>
                <li><a href="#" class="not-yet-available">Services/Rates</a></li>
                <li><a href="#" class="not-yet-available">Striping Vendors</a></li>
                <li><a href="#" class="not-yet-available">Striping Services</a></li>

            </ul>
        </li>
        <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown">
            <a href="javascript:;">Reports <i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu pull-left">
                <li><a href="#" class="not-yet-available">CRM</a></li>
                <li><a href="#" class="not-yet-available">Proposals</a></li>
                <li><a href="#" class="not-yet-available">Work Orders</a></li>
                <li><a href="#" class="not-yet-available">Labor</a></li>
            </ul>
        </li>
        @if (auth()->user()->hasRole('admin'))
            <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown">
                <a href="javascript:;">System <i class="fa fa-angle-down"></i></a>
                <ul class="dropdown-menu pull-left">
                    {{--
                    <li aria-haspopup="true" class="dropdown-submenu ">
                        <a href="javascript:;" class="nav-link nav-toggle ">Companies<span class="arrow"></span></a>
                        <ul class="dropdown-menu">
                            <li aria-haspopup="true"><a href="#" class="nav-link ">Companies</a></li>
                            <li aria-haspopup="true"><a href="#" class="nav-link ">Services</a></li>
                        </ul>
                    </li>
                    <li><a href="crm/showCRMList">Properties</a></li>
                    <li><a href="crm/select/">Vendors</a></li>
                    <li><a href="crm/servicesList/">Services </a></li>
                    --}}
                    @if (auth()->user()->hasPrivileges(['list-company-category', 'list-user-category']))
                        <li aria-haspopup="true" class="dropdown-submenu ">
                            <a href="javascript:;" class="nav-link nav-toggle ">Categories<span class="arrow"></span></a>
                            <ul class="dropdown-menu">
                                @if (auth()->user()->hasPrivilege('list-company-category'))
                                    <li aria-haspopup="true"><a href="{{ route('company_category_list') }}" class="nav-link ">Companies</a></li>
                                @endif
                                @if (auth()->user()->hasPrivilege('list-user-category'))
                                    <li aria-haspopup="true"><a href="{{ route('user_category_list') }}" class="nav-link ">Contacts</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    <li><a href="{{ route('age_period_list') }}">Age Periods</a></li>
                    <li><a href="{{ route('config_list') }}">Settings</a></li>

                    @if (auth()->user()->hasPrivileges(['list-role', 'list-privilege']))
                        <li aria-haspopup="true" class="dropdown-submenu ">
                            <a href="javascript:;" class="nav-link nav-toggle ">System Access<span class="arrow"></span></a>
                            <ul class="dropdown-menu">
                                @if (auth()->user()->hasPrivilege('list-role'))
                                    <li aria-haspopup="true"><a href="{{ route('role_list') }}" class="nav-link ">Roles</a></li>
                                @endif
                                @if (auth()->user()->hasPrivilege('list-privilege'))
                                    <li aria-haspopup="true"><a href="{{ route('privilege_list') }}" class="nav-link ">Privileges</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
    </ul>
</div>
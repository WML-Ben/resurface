<nav class="quick-nav">
    <a class="quick-nav-trigger" href="javascript:;">
        <span aria-hidden="true"></span>
    </a>
    <ul>
        <li><a data-toggle="modal" href="{{ route('task_create') }}" class="not-yet-available"><span>New Task</span><i class="icon-calendar"></i></a></li>
        <li><a data-toggle="modal" href="javascript:;" data-route="{{ route('proposal_create') }}" class="not-yet-available"><span>New Proposal</span><i class="fa fa-basket"></i></a></li>
        <li><a href="{{ route('lead_create') }}" class="show-spinner"><span>New Intake</span><i class="fa fa-phone"></i></a></li>
    </ul>
    <span aria-hidden="true" class="quick-nav-bg"></span>
</nav>
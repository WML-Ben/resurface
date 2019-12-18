<ul class="dropdown-menu extended tasks">
    <li class="external">
        <h3>You have
            <strong>{{ $myIncompleteTasks['total'] }} pending</strong> {{ str_plural('task', $myIncompleteTasks['total']) }}</h3>
        <a href="{{ route('task_list') }}" class="">view all</a>
    </li>
    <li>
        <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">
            @if (!empty($myIncompleteTasks['tasks']))
                @foreach ($myIncompleteTasks['tasks'] as $task)
                    <li>
                        <a href="{{ route('task_list') }}" class="not-yet-available">
                            <span class="task">
                                <span class="desc">{{ str_limit($task->name, 30) }}</span>
                                <span class="percent">{!! $task->days_left > 0 ? '<span class="success">'. $task->days_left . str_plural(' day', $task->days_left) . ' left</span>' : '<span class="danger">OVERDUE</span>' !!}</span>
                            </span>
                            <span class="progress">
                                <span style="width: {{ $task->percent_in_time }}%;" class="progress-bar progress-bar-{{ $task->days_left > 0 ? 'success' : 'danger' }}" aria-valuenow="{{ $task->percent_in_time }}" aria-valuemin="0" aria-valuemax="100">
                                    <span class="sr-only">{{ $task->due_at->format('m/d/Y') ?? '' }}</span>
                                </span>
                            </span>
                        </a>
                    </li>
                @endforeach
            @endif
        </ul>
    </li>
</ul>
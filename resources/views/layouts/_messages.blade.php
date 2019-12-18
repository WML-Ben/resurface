<ul class="dropdown-menu">
    <li class="external">
        <h3>You have
            <strong>{{ $myUnreadMessages['total'] }} New</strong> {{ str_plural('Message', $myUnreadMessages['total']) }}</h3>
        <a href="{{ route('message_received_list') }}" class="not-yet-available">view all</a>
    </li>
    <li>
        <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">
            @if (!empty($myUnreadMessages['messages']))
                @foreach ($myUnreadMessages['messages'] as $message)
                    <li>
                        <a href="{{ route('message_received_show', ['id' => $message->id ]) }}" class="not-yet-available">
                            <span class="photo">
                                @if (!empty($message->sender->avatar))
                                    <img src="{{ $mediaUrl.'/avatars/'.$message->sender->avatar }}" alt="{{ $message->sender->avatar }}" class="img-circle">
                                @else
                                    <img src="{{ $publicUrl.'/images/'.$config['defaultAvatar'] }}" alt="default avatar" class="mw40 br64 mr10">
                                @endif
                            </span>
                            <span class="subject">
                                <span class="from"> {{ $message->sender->fullName ?? 'Unknown' }} </span>
                                <span class="time">{{ $message->created_at->diffForHumans() }}</span>
                            </span>
                            <span class="message">{{ str_limit($message->subject, 100) }}</span>
                        </a>
                    </li>
                @endforeach
            @endif
        </ul>
    </li>
</ul>
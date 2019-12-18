<div class="progress-steps clearfix">
    <ul>
        @if (!empty($current) && $current == 'client')
            <li><a class="current" href="{{ !empty($no_link) ? 'javascript:;' : route('proposal_details_client', ['id' => $proposal->id]) }}">Client</a></li>
        @else
            <li><a href="{{ route('proposal_details_client', ['id' => $proposal->id]) }}">Client</a></li>
        @endif
        @if (!empty($current) && $current == 'services')
            <li><a class="current" href="{{ !empty($no_link) ? 'javascript:;' : route('proposal_details_services', ['id' => $proposal->id]) }}">Services</a></li>
        @else
            <li><a href="{{ route('proposal_details_services', ['id' => $proposal->id]) }}">Services</a></li>
        @endif
        @if (!empty($current) && $current == 'media')
            <li><a class="current" href="{{ !empty($no_link) ? 'javascript:;' : route('proposal_details_media', ['id' => $proposal->id]) }}">Media</a></li>
        @else
            <li><a href="{{  route('proposal_details_media', ['id' => $proposal->id]) }}">Media</a></li>
        @endif
        @if (!empty($current) && $current == 'preview')
            <li><a class="not-yet-available current" href="{{ !empty($no_link) ? 'javascript:;' : route('proposal_details_preview', ['id' => $proposal->id]) }}">Preview</a></li>
        @else
            <li><a class="not-yet-available" href="{{ route('proposal_details_preview', ['id' => $proposal->id]) }}">Preview</a></li>
        @endif
        @if (!empty($current) && $current == 'email')
            <li><a class="not-yet-available current" href="{{ !empty($no_link) ? 'javascript:;' : route('proposal_details_email', ['id' => $proposal->id]) }}">Email</a></li>
        @else
            <li><a class="not-yet-available" href="{{ route('proposal_details_email', ['id' => $proposal->id]) }}">Email</a></li>
        @endif
        @if (!empty($current) && $current == 'follow_up')
            <li><a class="not-yet-available current" href="{{ !empty($no_link) ? 'javascript:;' : route('proposal_details_follow_up', ['id' => $proposal->id]) }}">Follow Up</a></li>
        @else
            <li><a class="not-yet-available"  href="{{ route('proposal_details_follow_up', ['id' => $proposal->id]) }}">Follow Up</a></li>
        @endif
        @if (!empty($current) && $current == 'status')
            <li><a class="not-yet-available current" href="{{ !empty($no_link) ? 'javascript:;' : route('proposal_details_status', ['id' => $proposal->id]) }}">Status</a></li>
        @else
            <li><a class="not-yet-available" href="{{ route('proposal_details_status', ['id' => $proposal->id]) }}">Status</a></li>
        @endif

        <li><a class="not-yet-available finish" href="javascript:;" class="finish">{{ $proposal->status_id == 1 ? 'Approve' : 'Send'}} Proposal</a></li>
    </ul>
</div>
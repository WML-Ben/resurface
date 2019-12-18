<div class="text-center">
    <ul class="social-icon simple">
        {!! !empty($member->facebook) ? '<li><a href="' . $member->facebook . '" target="_blank" data-toggle="tooltip" data-placement="top" data-original-title="facebook"><i class="fa fa-facebook"></i></a></li>' : '' !!}
        {!! !empty($member->twitter) ? '<li><a href="' . $member->twitter . '" target="_blank" data-toggle="tooltip" data-placement="top" data-original-title="twitter"><i class="fa fa-twitter"></i></a></li>' : '' !!}
        {!! !empty($member->instagram) ? '<li><a href="' . $member->instagram . '" target="_blank" data-toggle="tooltip" data-placement="top" data-original-title="instagram"><i class="fa fa-instagram"></i></a></li>' : '' !!}
        {!! !empty($member->linkedin) ? '<li><a href="' . $member->linkedin . '" target="_blank" data-toggle="tooltip" data-placement="top" data-original-title="linkedin"><i class="fa fa-linkedin"></i></a></li>' : '' !!}
        {!! !empty($member->flickr) ? '<li><a href="' . $member->flickr . '" target="_blank" data-toggle="tooltip" data-placement="top" data-original-title="flickr"><i class="fa fa-flickr"></i></a></li>' : '' !!}
        {!! !empty($member->blog) ? '<li><a href="' . $member->blog . '" target="_blank" data-toggle="tooltip" data-placement="top" data-original-title="personal blog"><i class="fa fa-comment"></i></a></li>' : '' !!}
    </ul>
</div>
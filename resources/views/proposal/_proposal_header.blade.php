<div class="portlet mt-element-ribbon royal-blue box">
    <div class="proposal-status-ribbon ribbon-color-success uppercase">Status: {{ $proposal->status->name }}</div>

    <div class="portlet-title">
        <div id="porlet_caption_proposal_name" class="caption">{{ $proposal->name }}</div>
    </div>
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-12 static-info">
                <div class="name">Created for:</div>
                <div id="porlet_caption_company_name" class="value">{{ $proposal->company->name ?? '' }}</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7 static-info">
                <div class="name">Property Name:</div>
                <div id="porlet_caption_property_name" class="value">{{ $proposal->property->name ?? '' }}</div>
            </div>
            <div class="col-md-5 static-info">
                <div class="name">Age:</div>
                <div class="value">{{ !empty($proposal->created_at) ? $proposal->created_at->diffInDays(now()) . ' ' . str_plural('day', $proposal->created_at->diffInDays(now())) : '' }}</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7 static-info">
                <div class="name">Created Date:</div>
                <div class="value">{{ !empty($proposal->created_at) ? $proposal->created_at->format('l, F d, Y') : '' }}</div>
            </div>
            <div class="col-md-5 static-info">
                <div class="name">Last Update Date:</div>
                <div class="value">{{ !empty($proposal->updated_at) ? $proposal->updated_at->format('l, F d, Y') : '' }}</div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7 static-info">
                <div class="name">Created By:</div>
                <div class="value">{{ $proposal->createdBy->fullName ?? '' }}</div>
            </div>
            <div class="col-md-5 static-info">
                <div class="name">Last Update By:</div>
                <div class="value">{{ $proposal->updatedBy->fullName ?? '' }}</div>
            </div>
        </div>
    </div>
</div>
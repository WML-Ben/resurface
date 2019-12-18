{{-- $service --}}
<div class="portlet blue-hoki box" id="service_location_section">
    <div class="portlet-title">
        <div class="caption">Proposal Text</div>
    </div>
    <div class="portlet-body">
        <div class="row">
            <div class="col-xs-12 col-sm-6 admin-form-item-widget" id="show_proposal_text_container">
                {{ Form::jShow($obj->text ?? '&nbsp;', ['label' => 'Suggested Proposal Text', 'id' => 'show_proposal_text']) }}
            </div>
            <div class="col-xs-12 col-sm-6 admin-form-item-widget">
                {!! Form::jMce('proposal_text', ['required' => false, 'id' => 'proposal_text', 'label' => 'Actual Proposal Text', 'value' => $obj->text ?? '' ]) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <a href="javascript:;" id="reset_proposal_text_button"
                   class="btn btn-info">Reset Proposal Text</a>
            </div>
        </div>
    </div>
</div>

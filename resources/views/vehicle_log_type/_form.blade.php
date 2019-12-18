<div class="panel-body">
    <div class="section-divider mb40 mt20"><span>{{ $formTitle }}</span></div>

    <div class="row">
        <div class="col-md-12 admin-form-item-widget">
            {{ Form::jText('name', ['label' => 'Name', 'id' => 'name', 'placeholder' => '', 'required' => true]) }}
        </div>
    </div>

</div>
<div class="panel-footer text-right">
    <div class="row">
        <div class="col-sm-12">
            {{ Form::jCancelSubmit(['submit-label' => $submitButtonText]) }}
        </div>
    </div>
</div>
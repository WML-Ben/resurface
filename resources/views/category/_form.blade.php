<div class="panel-body">
    <div class="section-divider mb40 mt20"><span>{{ $formTitle }}</span></div>

    <div class="row">
        <div class="col-md-8 col-sm-7 admin-form-item-widget">
            {{ Form::jText('name', ['label' => 'Name', 'id' => 'name', 'placeholder' => '', 'required' => true]) }}
        </div>
        <div class="col-md-4 col-sm-5 admin-form-item-widget">
            {{ Form::jSelect('type_id', $typesCB, ['label' => 'Type', 'selected' => $category->type_id ?? null, 'required' => true, 'iconClass' => 'fa fa-bars', 'attributes' => ['id' => 'type_id']]) }}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 admin-form-item-widget">
            {{ Form::jTextarea('description', ['label' => 'Description', 'id' => 'description', 'placeholder' => '', 'required' => false]) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-xs-12 admin-form-item-widget">
            {{ Form::jSwitch('do_not_delete', ['label' => 'Do Not Delete', 'id' => 'do_not_delete', 'checked' => !empty($category->do_not_delete)]) }}
        </div>
        <div class="col-sm-6 col-xs-12 admin-form-item-widget">
            {{ Form::jSwitch('wizard', ['label' => 'Wizard', 'id' => 'wizard', 'checked' => !empty($category->wizard)]) }}
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
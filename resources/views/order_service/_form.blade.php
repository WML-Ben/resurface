<div class="panel-body">
    <div class="section-divider mb40 mt20"><span>{{ $formTitle }}</span></div>

    <div class="row">
        <div class="col-md-6 admin-form-item-widget">
            {{ Form::jText('name', ['label' => 'Privilege Name', 'id' => 'name', 'placeholder' => 'Privilege Name', 'required' => true]) }}
        </div>
        <div class="col-md-6 mt15 admin-form-item-widget">
            @if (!empty($create))
                {{ Form::jCheckbox('createCrud', ['label' => 'Create CRUD set', 'title' => 'CRUD: list, search, show, create, update and delete']) }}
            @endif
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
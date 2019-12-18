<div class="panel-body">
    <div class="section-divider mb40 mt20"><span>{{ $formTitle }}</span></div>

    <div class="row">
        <div class="col-md-12 admin-form-item-widget">
            {{ Form::jText('name', ['label' => 'Name', 'id' => 'name', 'placeholder' => '', 'required' => true]) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jText('initial_day', ['label' => 'Initial Day', 'hint' => '(positive number)', 'id' => 'initial_day', 'placeholder' => 'leave blank for less than Final Day', 'required' => false]) }}
        </div>
        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jText('final_day', ['label' => 'Final Day', 'hint' => '(positive number)', 'id' => 'final_day', 'placeholder' => 'leave blank for more than Initial Day', 'required' => false]) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jText('icon_class', ['label' => 'Icon Class', 'hint' => '(like "fa -fa-check")', 'id' => 'icon_class', 'placeholder' => 'icon class', 'required' => false]) }}
        </div>
        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jText('icon_color', ['label' => 'Icon Color', 'hint' => '(like #C0C0C0', 'id' => 'icon_color', 'placeholder' => 'color code', 'required' => false]) }}
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
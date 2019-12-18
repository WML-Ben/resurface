{!! Form::hidden('avatar', null, ['id' => 'avatar']) !!}
{!! Form::hidden('old_avatar', $employee->avatar ?? null, ['id' => 'old_avatar']) !!}

{!! Form::hidden('signature', null, ['id' => 'signature']) !!}
{!! Form::hidden('old_signature', $employee->signature ?? null, ['id' => 'old_signature']) !!}

<div class="panel-body">
    <div class="section-divider mb20 mt20"><span>{{ $formTitle }}</span></div>

    <div class="row mt20">
        <div class="col-sm-4 admin-form-item-widget">
            {{ Form::jDropzone('avatarFileField', route('employee_ajax_upload_image'), route('employee_ajax_delete_image'),
                array_merge([
                    'hidden-field-id' => 'avatar',
                    'class'           => 'user',
                    'label'           => 'Picture',
                    'hint'            => '(150 x 150 px)',
                    'width'           => '150',
                    'height'          => '150',
                    'required'        => false,
                ], !empty($employee->avatar) ? ['image' => $mediaUrl.'/avatars/'.$employee->avatar, 'id' => $employee->id] : []
            )) }}
        </div>

        <div class="col-sm-8 admin-form-item-widget">
            {{ Form::jDropzone('signatureFileField', route('employee_ajax_upload_image'), route('employee_ajax_delete_signature'),
                array_merge([
                    'hidden-field-id' => 'signature',
                    'class'           => 'signature',
                    'label'           => 'Signature',
                    'hint'            => '(450 x 150 px)',
                    'width'           => '450',
                    'height'          => '150',
                    'required'        => false,
                ], !empty($employee->signature) ? ['image' => $mediaUrl.'/signatures/'.$employee->signature, 'id' => $employee->id] : []
            )) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 admin-form-item-widget">
            <div class="w150">
            {{ Form::jText('salutation', ['label' => 'Salutation', 'id' => 'salutation', 'required' => false, 'iconClass' => 'icon-star']) }}
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4 admin-form-item-widget">
            {{ Form::jText('first_name', ['label' => 'First Name', 'id' => 'first_name', 'placeholder' => '', 'required' => true, 'iconClass' => 'fa fa-user']) }}
        </div>
        <div class="col-sm-4 admin-form-item-widget">
            {{ Form::jText('middle_name', ['label' => 'Middle Name', 'id' => 'middle_name', 'placeholder' => '', 'required' => false, 'iconClass' => 'fa fa-user']) }}
        </div>
        <div class="col-sm-4 admin-form-item-widget">
            {{ Form::jText('last_name', ['label' => 'Last Name', 'id' => 'last_name', 'placeholder' => '', 'required' => true, 'iconClass' => 'fa fa-user']) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jText('email', ['label' => 'Email', 'id' => 'email', 'placeholder' => 'Email...', 'required' => true, 'iconClass' => 'fa fa-envelope']) }}
        </div>
        <div class="col-sm-6 admin-form-item-widget">
            @if (empty($profile) && auth()->user()->hasRole('admin'))
                {{ Form::jSelect('role_id', $rolesCB, ['label' => 'Role', 'selected' => $roleId, 'id' => 'role_id', 'required' => true, 'iconClass' => 'icon-key']) }}
            @else
                {{ Form::jText(null, ['label' => 'Role', 'value' => $employee->role->name, 'id' => 'role_id_readonly', 'required' => true, 'iconClass' => 'icon-key',  'attributes' => ['readonly' => 'readonly']]) }}
                {!! Form::hidden('role_id', $employee->role_id) !!}
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 admin-form-item-widget">
            {{ Form::jPassword('password', ['label' => 'Password', 'id' => 'password', 'placeholder' => 'Password', 'required' => !empty($newemployee)]) }}
        </div>
        <div class="col-md-6 admin-form-item-widget">
            {{ Form::jPassword('repeat_password', ['label' => 'Repeat Password', 'id' => 'repeat_password', 'placeholder' => 'Repeat password', 'required' => !empty($newemployee), 'iconClass' => 'fa fa-unlock-alt']) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jText('phone', ['label' => 'Phone', 'id' => 'phone', 'placeholder' => '', 'required' => true, 'iconClass' => 'fa fa-phone']) }}
        </div>
        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jText('alt_phone', ['label' => 'Alt Phone', 'id' => 'alt_phone', 'placeholder' => '', 'required' => false, 'iconClass' => 'fa fa-phone']) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jText('title', ['label' => 'Title', 'id' => 'title', 'required' => false, 'iconClass' => 'icon-graduation-cap']) }}
        </div>
        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jText('alt_email', ['label' => 'Alt Email', 'id' => 'alt_email', 'placeholder' => '', 'required' => false, 'iconClass' => 'fa fa-envelope']) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 admin-form-item-widget">
            {{ Form::jText('address', ['label' => 'Address', 'id' => 'address', 'required' => false, 'iconClass' => 'icon-location']) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3 admin-form-item-widget">
            {{ Form::jText('address_2', ['label' => 'Address 2', 'id' => 'address_2', 'placeholder' => '', 'required' => false, 'iconClass' => 'icon-location']) }}
        </div>
        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jText('city', ['label' => 'City', 'id' => 'city', 'required' => false, 'iconClass' => 'icon-building']) }}
        </div>
        <div class="col-sm-3 admin-form-item-widget">
            {{ Form::jText('zipcode', ['label' => 'Zip Code', 'id' => 'zipcode', 'required' => false, 'iconClass' => 'fa fa-map-o']) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4 admin-form-item-widget">
            {{ Form::jSelect('country_id', $countriesCB, ['label' => 'Country', 'selected' => ($employee->country_id ?? 231), 'required' => false, 'iconClass' => 'fa fa-globe', 'attributes' => ['id' => 'country_id']]) }}
        </div>
        <div class="col-sm-4 admin-form-item-widget">
            {{ Form::jSelect('state_id', $statesCB, ['label' => 'State', 'selected' => ($employee->state_id ?? 3930), 'required' => false, 'iconClass' => 'fa fa-map-pin', 'attributes' => ['id' => 'state_id']]) }}
        </div>
        <div class="col-sm-4 admin-form-item-widget">
            {{ Form::jCalendar('date_of_birth', ['value' => ($employee->date_of_birth ?? null), 'label' => 'Date Of Birth', 'id' => 'date_of_birth', 'placeholder' => '', 'iconClass' => 'fa fa-calendar']) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4 admin-form-item-widget">
            {{ Form::jCalendar('hired_at', ['value' => ($employee->hired_at ?? null), 'label' => 'Date Of Hire', 'id' => 'hired_at', 'placeholder' => '', 'iconClass' => 'fa fa-calendar']) }}
        </div>
        <div class="col-sm-4 admin-form-item-widget">
            {{--
            {{ Form::jSelect('company_position_id', $companyPositionsCB, ['label' => 'Company Position', 'selected' => ($employee->company_position_id ?? null), 'required' => true, 'iconClass' => 'fa fa-bars', 'attributes' => ['id' => 'company_position_id']]) }}
            --}}
            {!! Form::hidden('company_position_id', 1) !!}
        </div>
        <div class="col-sm-4 xs-hidden admin-form-item-widget"></div>
    </div>
    <div class="row">
        <div class="col-sm-12 admin-form-item-widget">
            {{ Form::jTextarea('comment', ['label' => 'Comment', 'id' => 'comment', 'iconClass' => 'icon-comment-2']) }}
        </div>
    </div>

    @if (empty($employee) || auth()->user()->id != $employee->id)
        <div class="row mt10">
            <div class="col-md-12 admin-form-item-widget">
                {{ Form::jSwitch('disabled', ['label' => 'Disabled', 'id' => 'disabled', 'checked' => !empty($employee->disabled)]) }}
            </div>
        </div>
    @endif
</div>
<div class="panel-footer text-right">
    <div class="row">
        <div class="col-sm-12">
            {{ Form::jCancelSubmit(['submit-label' => $submitButtonText]) }}
        </div>
    </div>
</div>
{{--
{!! Form::hidden('avatar', null, ['id' => 'avatar']) !!}
{!! Form::hidden('old_avatar', $contact->avatar ?? null, ['id' => 'old_avatar']) !!}

{!! Form::hidden('signature', null, ['id' => 'signature']) !!}
{!! Form::hidden('old_signature', $contact->signature ?? null, ['id' => 'old_signature']) !!}
--}}
<div class="panel-body">
    <div class="section-divider mb20 mt20"><span>{{ $formTitle }}</span></div>

    {{--
    <div class="row mt20">
        <div class="col-sm-4 admin-form-item-widget">
            {{ Form::jDropzone('avatarFileField', route('contact_ajax_upload_image'), route('contact_ajax_delete_image'),
                array_merge([
                    'hidden-field-id' => 'avatar',
                    'class'           => 'user',
                    'label'           => 'Picture',
                    'hint'            => '(150 x 150 px)',
                    'width'           => '150',
                    'height'          => '150',
                    'required'        => false,
                ], !empty($contact->avatar) ? ['image' => $mediaUrl.'/avatars/'.$contact->avatar, 'id' => $contact->id] : []
            )) }}
        </div>

        <div class="col-sm-8 admin-form-item-widget">
            {{ Form::jDropzone('signatureFileField', route('contact_ajax_upload_image'), route('contact_ajax_delete_signature'),
                array_merge([
                    'hidden-field-id' => 'signature',
                    'class'           => 'signature',
                    'label'           => 'Signature',
                    'hint'            => '(450 x 150 px)',
                    'width'           => '450',
                    'height'          => '150',
                    'required'        => false,
                ], !empty($contact->signature) ? ['image' => $mediaUrl.'/signatures/'.$contact->signature, 'id' => $contact->id] : []
            )) }}
        </div>
    </div>
    --}}
    <div class="row">
        <div class="col-sm-2 admin-form-item-widget">
            {{ Form::jText('salutation', ['label' => 'Salutation', 'id' => 'salutation', 'required' => false, 'iconClass' => 'icon-star']) }}
        </div>
        <div class="col-sm-5 admin-form-item-widget">
            {{ Form::jSelect('category_id', $categoriesCB, ['label' => 'Category', 'selected' => ($contact->category_id ?? null), 'required' => true, 'iconClass' => 'fa fa-bars', 'attributes' => ['id' => 'category_id']]) }}
        </div>
        <div class="col-sm-5 admin-form-item-widget">
            {{ Form::jSelect2('company_id', [], ['label' => 'Company', 'selected' => $contact->company_id ?? null, 'required' => false, 'iconClass' => 'fa fa-sitemap', 'attributes' => ['id' => 'company_id']]) }}
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
            {{ Form::jText('email', ['label' => 'Email', 'id' => 'email', 'required' => false, 'iconClass' => 'fa fa-envelope']) }}
        </div>
        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jText('overhead', ['label' => 'Overhead', 'id' => 'overhead', 'required' => false, 'iconClass' => 'fa fa-bookmark']) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 admin-form-item-widget">
            {{ Form::jText('phone', ['label' => 'Phone', 'id' => 'phone', 'placeholder' => '', 'required' => false, 'iconClass' => 'fa fa-phone']) }}
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
            {{ Form::jSelect('country_id', $countriesCB, ['label' => 'Country', 'selected' => ($contact->country_id ?? 231), 'required' => false, 'title' => 'Este campo es requerido', 'iconClass' => 'fa fa-globe', 'attributes' => ['id' => 'country_id']]) }}
        </div>
        <div class="col-sm-4 admin-form-item-widget">
            {{ Form::jSelect('state_id', $statesCB, ['label' => 'State', 'selected' => ($contact->state_id ?? 3930), 'required' => false, 'title' => 'Este campo es requerido', 'iconClass' => 'fa fa-map-pin', 'attributes' => ['id' => 'state_id']]) }}
        </div>
        <div class="col-sm-4 admin-form-item-widget">
            {{ Form::jCalendar('date_of_birth', ['value' => ($contact->date_of_birth ?? null), 'label' => 'Date Of Birth', 'id' => 'date_of_birth', 'placeholder' => '', 'iconClass' => 'fa fa-calendar']) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 admin-form-item-widget">
            {{ Form::jTextarea('comment', ['label' => 'Comment', 'id' => 'comment', 'iconClass' => 'icon-comment-2']) }}
        </div>
    </div>

    @if (empty($contact) || auth()->user()->id != $contact->id)
        <div class="row mt10">
            <div class="col-sm-6 admin-form-item-widget">
                {{ Form::jSwitch('disabled', ['label' => 'Disabled', 'id' => 'disabled', 'checked' => !empty($contact->disabled)]) }}
            </div>
            <div class="col-sm-6 admin-form-item-widget">
                {{ Form::jSwitch('qualified', ['label' => 'Qualified', 'id' => 'qualified', 'checked' => !empty($contact->qualified)]) }}
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
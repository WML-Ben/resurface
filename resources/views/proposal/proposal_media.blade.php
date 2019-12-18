@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><a href="{{ route('proposal_list') }}">Proposals</a><i class="fa fa-angle-right"></i></li>
            <li><span>Details: <strong>Uploads</strong></span></li>
        </ul>
    </div>
@stop

@section('content')
    <section id="content" class="animated fadeIn list-items admin-form plr0">
        <div class="row">
            <div class="col-md-12" id="proposal_steps">
                <div id="main-wrapper p0">
                    <div id="content-wrapper">
                        <div class="stat-panel">
                            <div class="stat-row">
                                @include('proposal._tabs', ['current' => 'media', 'no_link' => true])

                                @include('errors._list')

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

                                <div id="upload_media" class="portlet blue-hoki box">
                                    <div class="portlet-title">
                                        <div class="caption">Media</div>
                                    </div>

                                    <div class="portlet-body">
                                        <div class="row mt15">
                                            <div class="col-md-4 col-sm-6">
                                                <div class="jumbotron how-to-create">
                                                    {!! Form::open(['url' => route('proposal_ajax_media_upload'), 'class' => 'dropzone', 'files' => true, 'id' => 'uploadMedia']) !!}
                                                    <div class="fallback">
                                                    </div>
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-sm-6 xs-mt15">
                                                <form action="#" id="media_data_dummy_form">
                                                    <div class="row">
                                                        <div class="col-sm-6 admin-form-item-widget">
                                                            {{ Form::jSelect('media_category_id', $mediaCategoriesCB, ['label' => 'Media Category', 'selected' => null, 'required' => true, 'class' => 'dummy-form-item', 'iconClass' => 'fa fa-bars', 'attributes' => ['id' => 'media_category_id']]) }}
                                                        </div>
                                                        <div class="col-sm-6 admin-form-item-widget">
                                                            {{ Form::jSelect('order_service_id', $existingServicesCB, ['label' => 'Service', 'selected' => null, 'required' => false, 'iconClass' => 'fa fa-gears', 'attributes' => ['id' => 'order_service_id']]) }}
                                                        </div>
                                                        <div class="col-sm-12 admin-form-item-widget">
                                                            {{ Form::jTextarea('description', ['label' => 'Description', 'id' => 'description', 'required' => false, 'class' => 'dummy-form-item', 'iconClass' => 'fa fa-comment']) }}
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="radio-checkbox-container vertical">
                                                    <label for="admin_only"  class="radio-checkbox-item">
                                                        <input id="admin_only" name="admin_only" class="checkbox" type="checkbox" value="1">
                                                        <i class="fa fa-square-o"></i>
                                                        <i class="fa fa-check-square-o"></i>
                                                        <span class="fs14 pl32">Restrict view to admin only</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 not-xs-tr xs-ptb15">
                                                <button id="upload_button" type="button" class="btn green">Upload Media</button>
                                            </div>
                                        </div>

                                        @if (!empty($proposal->media) && $proposal->media->count())
                                            <div class="section-divider mb20 mt20"><span>Attached Media</span></div>

                                            <div class="row plr10">
                                                <div class="col-sm-12 static-info table-scrollable plr0">
                                                    <table class="table table-bordered" id="media_table">
                                                        <thead>
                                                        <tr>
                                                            <th><strong>Media</strong></th>
                                                            <th class="tc"><strong>Category</strong></th>
                                                            <th class="tc"><strong>Service</strong></th>
                                                            <th class="tc"><strong>Uploaded</strong></th>
                                                            <th><strong>Actions</strong></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody id="attached_media_tbody">
                                                            @foreach ($proposal->media as $item)
                                                                <tr data-order_media_id="{{ $item->id }}">
                                                                    <td>{{ $item->original_file_name }}</td>
                                                                    <td class="tc">{{ $item->mediaCategory->name }}</td>
                                                                    <td class="tc">{{ $item->orderService->name ?? 'Attached to entire proposal' }}</td>
                                                                    <td class="tc">{{ $item->created_at->format('F d, Y') }}</td>
                                                                    <td>
                                                                        <a href="javascript:;" class="action ajax-delete" data-id="{{ $item->id }}">Remove</a>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('proposal._dropzone_template')
@stop

@section('css-page-level-plugins')
    {!! Html::style($publicUrl .'/css/html5imageupload.css') !!}
    {!! Html::style($publicUrl .'/assets/dropzone/dropzone.css') !!}
    {!! Html::style($publicUrl . '/css/tomcss.css') !!}
@stop

@section('js-page-level-scripts')
    {!! Html::script($publicUrl .'/js/html5imageupload.js') !!}
    {!! Html::script($publicUrl .'/assets/dropzone/min/dropzone.min.js') !!}
@stop

@section('js-files')
    <script>
        $(function(){
            var maxSizeMb = 10;

            Dropzone.options.uploadMedia = {
                paramName: 'mediafile',
                uploadMultiple: false,
                parallelUploads: 1,
                maxFilesize: maxSizeMb,

                addRemoveLinks: true,
                dictDefaultMessage: '<div class="icon-container"><i class="fa fa-cloud-upload"></i></div><div class="text-container">Drop file or click to browse ('+ maxSizeMb +' Mb max.)</div>',
                dictResponseError: 'Server not Configured',
                autoProcessQueue: false,
                createImageThumbnails: false,

                // The setting up of the dropzone
                init: function () {
                    var self = this;

                    var submitButton = document.querySelector('#upload_button');

                    submitButton.addEventListener('click', function() {
                        var valid = true;

                        if (self.getQueuedFiles().length == 0) {
                            notify({
                                type: 'error',
                                title: 'Error',
                                message: 'There is no file defined to be uploaded.',
                                addClass: 'mt50',
                                delay: 3000
                            });
                            valid = false;
                        }

                        $('#media_data_dummy_form').find('.dummy-form-item').each(function (index, elem) {
                            var isElemValid = $('#media_data_dummy_form').validate().element(elem);
                            if (isElemValid != null) { //this covers elements that have no validation rule
                                valid = valid & isElemValid;
                            }
                        });

                        if (valid) {
                            self.processQueue();
                        }

                        return false;
                    });

                    self.on('sending', function (file, xhr, formData) {
                        xhr.timeout = 99999999;
                        showSpinner();
                        formData.append('proposal_id', "{{ $proposal->id }}");
                        formData.append('media_category_id', $('#media_category_id').val());
                        formData.append('order_service_id', $('#order_service_id').val());
                        formData.append('description', $('#description').val());
                        formData.append('admin_only', $('#admin_only').is(':checked'));
                    });

                    self.on('complete', function(file) {
                        self.removeFile(file);
                    });
                },
                success: function (file, response) {
                    resetFormInputs($('#media_data_dummy_form'));

                    var html = '';
                    html += '<tr data-order_media_id="'+ response.order_media_id +'">';
                    html += '<td class="tl">'+ response.originalFileName +'</td>';
                    html += '<td class="tc">'+ response.media_category +'</td>';
                    html += '<td class="tc">'+ response.order_service +'</td>';
                    html += '<td class="tc">'+ response.created_at_str +'</td>';
                    html += '<td class="tl"><a href="javascript:;" class="action ajax-delete" data-id="'+ response.order_media_id +'">Remove</a></td>';
                    html += '</tr>';
                    $('#attached_media_tbody').append(html);

                    hideSpinner();

                    notify({
                        type: 'success',
                        title: 'Success',
                        message: response.message,
                        addClass: 'mt50',
                        delay: 3000
                    });
                },
                error: function (file, response) {
                    hideSpinner();

                    notify({
                        type: 'error',
                        title: 'Error',
                        message: response.message,
                        addClass: 'mt50',
                        delay: 3000
                    });
                }
            };

            $('#attached_media_tbody').on('click', '.ajax-delete', function(){
                var order_media_id = $(this).data('id');
                confirmation({
                    message: 'Are you sure to remove this media?',
                    confirm_function: function(){
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('proposal_ajax_media_delete') }}",
                            data: {
                                order_media_id: order_media_id
                            },
                            dataType: 'json',
                            beforeSend: function (request) {
                                showSpinner();
                            },
                            complete: function () {
                                hideSpinner();
                            },
                            success: function (response) {
                                if (response.success) {
                                    $('#attached_media_tbody').find('tr[data-order_media_id="'+ order_media_id +'"]').remove();

                                    notify({
                                        type: 'success',
                                        title: 'Success',
                                        message: response.original_file_name + ' successfully removed.',
                                        addClass: 'mt50',
                                        delay: 3000
                                    });
                                } else {
                                    notify({
                                        type: 'error',
                                        title: 'Error',
                                        message: response.message,
                                        addClass: 'mt50',
                                        delay: 3000
                                    });
                                }
                            }
                        });
                    }
                });
            });

            $('#media_data_dummy_form').validate({
                rules: {
                    media_category_id: {
                        required: true,
                        positive: true
                    },
                    description: {
                        required : false,
                        plainText: true
                    }
                },
                messages: {
                    media_category_id: {
                        positive: 'Please, select media category.'
                    }
                },
                submitHandler: function(form) {
                    return false;
                }
            });
        });
    </script>
@stop
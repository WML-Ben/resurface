@extends('layouts.layout')

@section('breadcrumbs')
    <div class="mb15">
        <ul class="page-breadcrumb breadcrumb">
            <li><a href="{{ route('dashboard') }}">Dashboard</a><i class="fa fa-angle-right"></i></li>
            <li><span>Intake Form</span></li>
        </ul>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-sm-6">
            <input type="text" id="file_input" class="form-control">
        </div>
        <div class="col-sm-1">
            <button type="button" class="btn btn-default" onclick="openElfinder()">Select image</button>
        </div>
		<div class="preview-image">
			
		</div>
    </div>
    <div class="modal fade" id="elfinderShow">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-body">
            <div id="elfinder"></div>
          </div>
        </div>
      </div>
    </div>

@endsection

@section('js-files')

<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />

<link rel="stylesheet" type="text/css" href="{{ asset('packages/barryvdh/elfinder/css/elfinder.min.css') }}">

<link rel="stylesheet" type="text/css" href="{{ asset('packages/barryvdh/elfinder/css/theme.css') }}">

<!-- elFinder JS (REQUIRED) -->
<script src="{{ asset('packages/barryvdh/elfinder/js/elfinder.min.js') }}"></script>

<script type="text/javascript">
    function openElfinder(){

        $('#elfinderShow').modal();

        $('#elfinder').elfinder({
            debug: false,
            lang: 'en',
            width: '100%',
            height: '80%',
            customData: {
                _token: '{{ csrf_token() }}'
            },
            commandsOptions: {
                getfile: {
                    onlyPath: true,
                    folders: false,
                    multiple: true,
                    oncomplete: 'destroy'
                },
                ui : 'uploadbutton'
            },
            mimeDetect: 'internal',
            onlyMimes: [
                'image/jpeg',
                'image/jpg',
                'image/png',
                'image/gif',
				'aplication/pdf'
            ],
            url: '{{ route("elfinder.connector") }}',
            soundPath: '{{ asset("packages/barryvdh/elfinder/sounds") }}',
            getFileCallback: function(file) {
				$('#file_input').val(file.length + ' files added');
				for(i = 0;i < file.length;i++)
				{
					$('.preview-image').append('<div class="img-thumb"><img src="'+file[i].url+'"/></div>');
				}
				
                $('#elfinderShow').modal('hide');
            },
            resizable: false
        }).elfinder('instance');

    }
</script>
@stop
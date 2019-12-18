@extends('layouts.error')

@section('content')
    <div class="container">
        <div class="row mt60">
            <div class="xs-hidden col-md-1"></div>
            <div class="col-sm-12 col-md-10">
                <div class="content-404">
                    <div class="relative background-500">
                        <div class="text-404">{{ $errorCode }}</div>
                        <h2 class="title-404 over-404">{!! $errorTitle !!}</h2>
                    </div>
                </div>
                <div class="text-center">
                    <p class="error-message">{!! $errorText !!}</p>
                    <div class="mt30">
                        <a href="{{ asset('/') }}" class="btn btn-info">{!! ($lang == 'sp') ? 'Ir a Inicio' : 'Go home' !!}</a>
                    </div>
                </div>
            </div>
            <div class="xs-hidden col-md-1"></div>
        </div>
    </div>
@stop

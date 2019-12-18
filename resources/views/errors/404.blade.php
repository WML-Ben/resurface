@extends('layouts.error')

@section('content')
    <div class="container">
        <div class="row mt60">
            <div class="xs-hidden col-md-1"></div>
            <div class="col-sm-12 col-md-10">
                <div class="content-404">
                    <div class="relative background">
                        <div class="text-404">404</div>
                        <h2 class="title-404 over-404">Oops, This Page Could Not Be Found!</h2>
                    </div>
                </div>
                <div class="text-center">
                    <p class="error-message">Unfortunately the page you were looking for could not be found. It may be temporarily unavailable, moved or no longer exist. Check the URL you entered for any mistakes and try again. Alternatively, you could take a look around the rest of our site.</p>
                    <div class="mt30">
                        <a href="{{ asset('/') }}" class="btn btn-info">Go home</a>
                    </div>
                </div>
            </div>
            <div class="xs-hidden col-md-1"></div>
        </div>
    </div>
@stop

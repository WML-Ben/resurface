@extends('layouts.error')

@section('content')
    <div class="container">
        <div class="row mt60">
            <div class="xs-hidden col-md-1"></div>
            <div class="col-sm-12 col-md-10">
                <div class="content-404">
                    <div class="relative background-500">
                        <div class="text-404">500</div>
                        <h2 class="title-404 over-404">{!! ($lang == 'sp') ? 'Ha Ocurrido Un Error Grave' : 'A Critical Server Error Has Occurred' !!}</h2>
                    </div>
                </div>
                <div class="text-center">
                    <p class="error-message">
                        @if ($lang == 'sp')
                            Desafortunadamente ha ocurrido un error grave. Le ofrecemos nuestras disculpas y le aseguramos que nuestro equipo t&eacute;cnico lo resolver&aacute; en breve. Gracias!
                        @else
                            Unfortunately a critical server error has occurred. We apologize for this and we assure you our technical team will fix it shortly. Thanks!
                        @endif
                    </p>
                    <div class="mt30">
                        <a href="{{ asset('/') }}" class="btn btn-info">{!! ($lang == 'sp') ? 'Ir a Inicio' : 'Go home' !!}</a>
                    </div>
                </div>
            </div>
            <div class="xs-hidden col-md-1"></div>
        </div>
    </div>
@stop

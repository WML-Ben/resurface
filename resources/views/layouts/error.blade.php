<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>

@if (env('APP_ENV') != 'production')
    <!-- To prevent most search engine web crawlers from indexing a page -->
        <meta name="robots" content="noindex">
    @endif

    <title>{!! isset($seo['pageTitlePrefix']) ? html_entity_decode($seo['pageTitlePrefix']) : 'Error 404 | ' !!}{!! isset($seo['pageTitle']) ? html_entity_decode($seo['pageTitle']) : (isset($lang) && $lang == 'sp' ? 'Página no encontrada' : 'Page not found') !!}</title>

    <link rel="shortcut icon" href="/images/favico.ico">

    <!-- CSS files -->
    {!! Html::style('//fonts.googleapis.com/css?family=Lato:100') !!}

    {!! Html::style('/assets/bootstrap-3.3.4/bootstrap.min.css') !!}
    {!! Html::style('/assets/font-awesome-4.5.0/css/font-awesome.min.css') !!}

<!-- Main CSS files -->
    {!! Html::style('/css/important.css') !!}
    {!! Html::style('/css/error.css') !!}
</head>
<body>
    @yield('content')

    <!-- Javascript files -->
    {!! Html::script('/assets/jquery-2.1.0/jquery-2.1.0.min.js') !!}
    <script>
        $(document).ready(function(){
            checkViewport();

            var waitForFinalEvent=function(){var b={};return function(c,d,a){a||(a="I am a banana!");b[a]&&clearTimeout(b[a]);b[a]=setTimeout(c,d)}}();
            var fullDateString = new Date();

            $(window).resize(function () {
                waitForFinalEvent(function(){
                    checkViewport();
                }, 100, fullDateString.getTime())
            });
        });
        function checkViewport()
        {
            $('body').removeClass('xs').removeClass('sm').removeClass('md').removeClass('lg');
            if (isBreakpoint('xs') ) {
                $('body').addClass('xs');
            } else if( isBreakpoint('sm') ) {
                $('body').addClass('sm');
            } else if( isBreakpoint('md') ) {
                $('body').addClass('md');
            } else if( isBreakpoint('lg') ) {
                $('body').addClass('lg');
            } else {
                $('body').addClass('xs');
            }
        }
        function isBreakpoint(alias) {
            return $('.device-' + alias).is(':visible');
        }
        function getBreakpoint() {
            return $('.viewport-item:visible').data('viewport');
        }

    </script>

    <!-- page specific js code -->
    @yield('js-page-code')

    <div class="device-xs visible-xs viewport-item" data-viewport="xs"></div>
    <div class="device-sm visible-sm viewport-item" data-viewport="sm"></div>
    <div class="device-md visible-md viewport-item" data-viewport="md"></div>
    <div class="device-lg visible-lg viewport-item" data-viewport="lg"></div>
</body>
</html>

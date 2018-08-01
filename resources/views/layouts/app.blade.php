<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ config('app.name', 'AviaionToolbox') }}
        @if(isset($title) and $title != '')
            +++ {{$title}}
        @endif
    </title>

    <style>
        @yield('style')
    </style>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
</head>
<body>
    <a href="https://github.com/you">
        <img style="position: absolute; top: 0; left: 0; border: 0; z-index: 100;" src="https://s3.amazonaws.com/github/ribbons/forkme_left_green_007200.png" alt="Fork me on GitHub">
    </a>

    <header>
        <div class="collapse bg-dark" id="navbarHeader">
            <div class="container">
                <div class="row">
                    <div class="col-sm-4 py-4">
                        <h4 class="text-white"><i class="fas fa-plane-arrival"></i>&nbsp;Arriving Tools</h4>
                        <ul class="list-unstyled">
                            <li><a href="{{action('AutobrakeController@show')}}" class="text-white">Boeing 738 NG Autobrake Calculator</a></li>
                            <li><a href="{{action('DescendCalculatorController@show')}}" class="text-white">Descent Calculator</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-4 py-4">
                        <h4 class="text-white"><i class="fas fa-toolbox"></i>&nbsp;General Tools</h4>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-white">E6B Emulator</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-4 py-4">
                        <h4 class="text-white">Contact</h4>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-white">Follow on Twitter</a></li>
                            <li><a href="#" class="text-white">Like on Facebook</a></li>
                            <li><a href="#" class="text-white">Email me</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar navbar-dark bg-dark shadow-sm">
            <div class="container d-flex justify-content-between">
                <a href="#" class="navbar-brand d-flex align-items-center">
                    <i class="fas fa-plane"></i>&nbsp;
                    <strong>{{ config('app.name', 'AviaionToolbox') }}</strong>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
    </header>

    <main role="main">
        <div class="container" style="padding-top: 40px;">
            @yield('content')
        </div>
    </main>

    <!-- Scripts -->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.6/angular.min.js"></script>-->
    <!--<script src="/toolbox/js/timeadder.js"></script>-->
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        jQuery(document).ready(function( $ ) {
            @yield('javascript')
        });
    </script>
</body>
</html>

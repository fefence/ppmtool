<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>bhapp.eu</title>

    @include('partials.include_js')
    @include('partials.include_css')
</head>

<body>

<div class="container">


    <!-- Static navbar -->
    <nav class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand navbar-brand-first" href="/play"><span class="{{Request::path() == 'play' ? 'text-danger' : '';}}">Play</span></a>
                <a class="navbar-brand" href="/series"><span class="{{Request::path() == 'series' ? 'text-danger' : '';}}">Series</span></a>
<!--                <li><a href="/list">List</a></li>-->
                @if (isset($fromdate) && isset($base))
                <?php
                    $from = $fromdate->toDateString();
                    $from1 = $fromdate->addDay()->addDay()->toDateString();
                ?>
                @if ($from == date('Y-m-d', time()))
                <a class="navbar-brand" href="/{{$base}}">&nbsp;<&nbsp;</a>
                @else
                <a class="navbar-brand" href="/{{$base}}/{{$from}}/{{$from}}">&nbsp;<&nbsp;</a>
                @endif
                @if ($from1 == date('Y-m-d', time()))
                <a class="navbar-brand" href="/{{$base}}">&nbsp;>&nbsp;</a>
                @else
                <a class="navbar-brand" href="/{{$base}}/{{$from1}}/{{$from1}}">&nbsp;>&nbsp;</a>
                @endif
                @else
<!--                <a class="navbar-brand disabled" href="#">&nbsp;<&nbsp;</a>-->
<!--                <a class="navbar-brand disabled" href="#">&nbsp;>&nbsp;</a>-->
                @endif
                <a class="navbar-brand" href="/list"><span class="{{Request::path() == 'list' ? 'text-danger' : '';}}">List</span></a>
                <!--                <a class="navbar-brand" href="/settings"><span class="{{Request::path() == 'settings' ? 'text-danger' : '';}}">Settings</span></a>-->
<!--                <a class="navbar-brand" href="/play/odds/all">Odds</a>-->
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="/active">Active</a></li>
                    <li><a href="/stats">Stats</a></li>
                    <li><a href="/log">Log</a></li>
                    <li><a href="/play/odds/all">Odds</a></li>
<!--                    <li class="divider"></li>-->
                    <li><a href="/logout">Log out</a></li>

<!--                    <li class="dropdown">-->
<!--                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Tools<span class="caret"></span></a>-->
<!--                        <ul class="dropdown-menu" role="menu">-->
<!--                            <li><a href="/log">Action log</a></li>-->
<!---->
<!--                            <li><a href="/stats">Statistics</a></li>-->
<!--                            <li><a href="/live">Livescore</a></li>-->
<!--                            <li><a href="/play/odds/all">Odds</a></li>-->
<!--                            <li class="divider"></li>-->
<!--                            <li><a href="/logout">Log out</a></li>-->
<!--                        </ul>-->
<!--                    </li>-->
                </ul>
                <p class="navbar-text navbar-right">{{$global}} €</p>
                <p class="navbar-text navbar-right">{{\Carbon\Carbon::now()->format("D, d M, H:i")}}</p>
            </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
    </nav>

    <!-- Main component for a primary marketing message or call to action -->
    @yield('content')


</div> <!-- /container -->

@include('partials.qtip')
</body>
</html>
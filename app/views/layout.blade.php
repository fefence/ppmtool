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
                <a class="navbar-brand" href="/play"><span class="{{Request::path() == 'play' ? 'text-danger' : '';}}">Play</span></a>
                <a class="navbar-brand" href="/series"><span class="{{Request::path() == 'series' ? 'text-danger' : '';}}">Series</span></a>
                @if (isset($fromdate))
                <?php
                    $from = $fromdate->subDay()->toDateString();
                    $from1 = $fromdate->addDay()->addDay()->toDateString();
                ?>
                <a class="navbar-brand" href="/play/{{$from}}/{{$from}}"><span><</span></a>
                <a class="navbar-brand" href="/play/{{$from1}}/{{$from1}}"><span>></span></a>
                @endif
<!--                <a class="navbar-brand" href="/settings"><span class="{{Request::path() == 'settings' ? 'text-danger' : '';}}">Settings</span></a>-->
<!--                <a class="navbar-brand" href="/play/odds/all">Odds</a>-->
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Tools<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Action log</a></li>
                            <li><a href="#">Statistics</a></li>
                            <li><a href="/live">Livescore</a></li>
                            <li><a href="/play/odds/all">Odds</a></li>
                            <li class="divider"></li>
<!--                            <li class="dropdown-header">Nav header</li>-->
                            <li><a href="/settings">Settings</a></li>
                            <li><a href="/logout">Log out</a></li>
                        </ul>
                    </li>
                </ul>
                <p class="navbar-text navbar-right">{{$global}} €</p>
            </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
    </nav>

    <!-- Main component for a primary marketing message or call to action -->
    @yield('content')


</div> <!-- /container -->

</body>
</html>
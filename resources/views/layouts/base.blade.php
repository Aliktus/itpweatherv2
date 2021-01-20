<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ITpweather @hasSection('title') - @yield('title') @endif</title>
        <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;700;900&display=swap" rel="stylesheet">
        @yield('stylesheets')
    </head>
    <body>
        @yield('body')
        @yield('javascripts')
    </body>
</html>

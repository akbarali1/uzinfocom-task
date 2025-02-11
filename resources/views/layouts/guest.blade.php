<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ITV.uz Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @if(config('app.env') === 'development')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/github-fork-ribbon-css/0.2.3/gh-fork-ribbon.min.css"/>
    @endif
</head>
<body style="background-color: #1e1e1e;">
@if(config('app.env') === 'development')
    <a class="github-fork-ribbon left-bottom fixed" target="_blank" href="https://github.com/akbarali1/uzinfocom-task" data-ribbon="Fork me on GitHub" title="Fork me on GitHub">Fork me on GitHub</a>
@endif
<div class="container">
    @yield('content')
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

     <link rel="stylesheet" media="screen"  href="{{asset('/print/main.css')}}">
     <link rel="stylesheet"  media="print" href="{{asset('/print/reciept.css')}}">
<style>
.page-break {
    page-break-after: always;
}
</style>
</head>
<body>
    <main>
        <div class="content">
            @yield('content')
        </div>
    </main>
</body>
</html>

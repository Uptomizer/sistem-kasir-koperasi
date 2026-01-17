<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Koperasi SMK')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 text-gray-800">

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

</body>
</html>

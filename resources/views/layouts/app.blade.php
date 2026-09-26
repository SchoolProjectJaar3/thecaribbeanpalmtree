<!-- app, hier wordt de app layout gedefinieerd -->
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'The Caribbean Palm Tree' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-sand-white text-deep-blue">

    @include('components.site.header')

    <main>
        @yield('content')
    </main>

    @include('components.site.footer')

</body>

</html>
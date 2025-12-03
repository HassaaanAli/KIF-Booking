<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- Resource Hints for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="dns-prefetch" href="https://www.googletagmanager.com" />
    <link rel="dns-prefetch" href="https://www.clarity.ms" />
    <link rel="dns-prefetch" href="https://platform-api.sharethis.com" />

    <!-- Optimized Font Loading with font-display: swap -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"
        media="print" onload="this.media='all'" />

    @yield('og')

    <title>@yield('meta_title', 'Home')</title>
    <meta name="description" content="@yield('meta_description', '')">
    <meta name="author" content="Abdulkader Safi" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="w-full min-h-screen bg-white">
    @yield('content')
</body>

</html>

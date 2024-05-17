<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!--Show the title of the create-->
        <title>3 cups 1 ball</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="{{ asset('js/3cups-1ballJs.js') }}" defer></script>

        <!-- Bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/dashboardCss.css') }}" rel="stylesheet">
        <style>
            .cup {
                width: 100px;
                height: 100px;
                background-color: #f1f1f1;
                border-radius: 50%;
                border: 1px solid #ccc;
                display: inline-block;
                margin: 10px;
                position: relative;
            }

            .ball {
                width: 20px;
                height: 20px;
                background-color: #333;
                border-radius: 50%;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            }

            #result {
                margin-top: 20px;
                font-size: 20px;
                color: white
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            <header class="dark:bg-gray-800 text-light shadow-sm">
                <div class="container py-3 px-3">
                    <h2 class="font-weight-bold h4">3 cups 1 ball</h2>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>
        </div>
    </body>
</html>

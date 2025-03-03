<!DOCTYPE html>
<html>

<head>
    <title>@yield('title', '掲示板')</title>
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/image-preview.js'])
</head>

<body>
    <header>
        <h1><a href="{{ route('boards.index') }}"><img src="{{ url('images/title.png') }}" alt="掲示板のタイトル画像"
                    style="max-width: 100px"></a></h1>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; 2025 掲示板</p>
    </footer>

    @livewireScripts
</body>

</html>

<!DOCTYPE html>
<html>

<head>
    <title>@yield('title', '掲示板')</title>
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/image-preview.js'])
    <style>
        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 20px;
            background-color: #333;
            color: white;
        }

        header div {
            margin-right: 5%;
        }

        .title {
            flex-grow: 0;
        }

        .nav-links {
            display: flex;
            flex-grow: 1;
        }

        .nav-links div {
            margin-right: 5%;
        }

        header h1 {
            font-size: 1.2rem;
        }
    </style>
</head>

<body>
    <header>
        <div class="title">
            <h1><a href="{{ route('boards.index') }}"><img src="{{ url('images/title.png') }}" alt="掲示板のタイトル画像"
                        style="max-width: 100px"></a></h1>
        </div>
        <div class="nav-links">
            <div class="boards">
                <h1><a href="{{ route('boards.index') }}">スレッド一覧</a></h1>
            </div>
            <div class="activity">
                <h1><a href="{{ route('activities.index') }}">アクティビティ</a></h1>
            </div>
        </div>
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

@extends('layouts.app')

@section('title', 'アクティビティ')

@section('content')
    <h1>アクティビティ</h1>

    <h2>あなたの立てたスレ</h2>
    @if ($boards->isEmpty())
        <p>まだスレッドを立てていません。</p>
    @else
        @foreach ($boards as $board)
            <div class="thread">
                <h3>
                    <a href="{{ route('boards.show', $board) }}">
                        {{ $board->title }}
                    </a>
                </h3>
                <p class="board-date">{{ $board->created_at->format('Y-m-d H:i') }}</p>
            </div>
        @endforeach
    @endif

    <h2>投稿したレス</h2>
    @if ($posts->isEmpty())
        <p>まだレスを投稿していません。</p>
    @else
        @foreach ($posts as $post)
            <div class="thread">
                <p>
                    <a href="{{ route('boards.show', $post) }}">
                        {{ $post->body }}
                    </a>
                    {{ $post->created_at->format('Y-m-d H:i') }}
                </p>
            </div>
        @endforeach
    @endif
@endsection

@extends('layouts.app')

@section('title', $board->title)

@section('content')
    <h1>{{ $board->title }}</h1>

    {{-- 投稿一覧 --}}
    @foreach ($posts as $index => $post)
        <div class="post">
            <div class="resheader">
                <span>{{ '>>' . ($index + 1) }}</span> {{-- レス番号 --}}
                <span>{{ $post->poster_name }}</span> {{-- 投稿者名(デフォルト: Post::DEFAULT_POSTER_NAME) --}}
                <span>{{ $post->created_at->format('Y/m/d H:i:s') }}</span> {{-- 投稿日時 --}}
                <button type="button" class="reply-button btn-small">返信</button>
                {{-- <button type="button" class="like-button btn-small">いいね</button> --}}
                @if ($post->ip_address === Request::ip() || $board->ip_address === Request::ip())
                    <form action="{{ route('boards.posts.destroy', [$board, $post]) }}" method="POST" class="delete-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="delete-button btn-small">削除</button>
                    </form>
                @endif
            </div>
            <div class="resbody">
                <p>{!! nl2br(e($post->body)) !!}</p> {{-- 改行を<br>タグに変換 --}}
            </div>
            @if ($post->image_path)
                <a href="{{ asset($post->image_path) }}" target="_blank">
                    <img src="{{ asset($post->image_path) }}" alt="投稿画像" style="max-width: 40%;"></a>
            @endif
        </div>
    @endforeach

    {{-- 新規投稿フォーム --}}
    @include('components.post-form', [
        'action' => route('boards.posts.store', $board),
        'isThread' => false,
    ])
    <p><a href="{{ route('boards.index') }}">スレッド一覧に戻る</a></p>
@endsection

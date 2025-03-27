@extends('layouts.app')

@section('title', 'スレッド一覧')

@section('content')
    <h1>スレッド一覧</h1>

    {{-- スレッド一覧 --}}
    @foreach ($boards as $board)
        <div class="thread">
            <h2>
                <a href="{{ route('boards.show', $board) }}">
                    {{ $board->title }}
                </a>
            </h2>
            <p>作成日時: {{ $board->created_at }}</p>
            @if ($board->ip_address === Request::ip())
                <form action="{{ route('boards.destroy', $board) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-button btn-small">削除</button>
                </form>
            @endif
        </div>
    @endforeach

    {{-- スレッド作成フォーム --}}
    @include('components.post-form', [
        'action' => route('boards.store'),
        'isThread' => true,
    ])
@endsection

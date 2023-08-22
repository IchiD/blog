@extends('layouts.app')

@section('content')

<main class="container">
    <!-- フラッシュメッセージの表示 -->
    @if(session('success'))
    <div class="alert alert-success mt-1">{{ session('success') }}</div>
    @elseif(session('error'))
    <div class="alert alert-danger mt-1">{{ session('error') }}</div>
    @endif

    <form method="POST" action="/" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input class="form-control my-2" type="text" name="title" placeholder="タイトル">
        <textarea class="form-control my-2" name="content" rows="4" placeholder="本文" required></textarea>
        <input type="file" name="img">
        <input class="form-control my-2" type="submit" value="送信">
    </form>



    @forelse ( $topics as $topic )
    <!-- forelseは空の場合の処理も書けるlaravelBladeの機能。foreachで書く場合は別にif文などで空チェックをする必要がある。 -->
    <div class="border my-2 p-2">
        <div class="p-1">{{ $topic->title }}</div>
        @if ($topic->imgpath)
        <img src="{{ asset('storage/thumbnails/' . $topic->imgpath) }}" alt="投稿画像" data-toggle="modal" data-target="#imageModal">
        @endif
        <div class="topic-content p-2">{!! nl2br(e($topic->content)) !!}</div>
        <div class="text-secondary">{{ $topic->likes()->count() }}いいね</div>
        <div class="text-secondary">投稿者:{{ $topic->name }} さん</div>
        <div class="text-secondary">投稿日:{{ $topic->created_at }}</div>
        @if ($topic->updated_at->timestamp !== $topic->created_at->timestamp)
        <div class="text-secondary">更新日:{{ $topic->updated_at }}</div>
        @endif
        <a href="{{ route('topic.detail', $topic->id) }}" class="btn btn-primary">詳細</a>

        <!-- 現在のユーザーのIDと投稿のユーザーIDを比較 -->
        @if(auth()->check() && auth()->id() === $topic->user_id)
        <div class="flex mt-3">
            <!-- 削除ボタンの表示 -->
            <form action="{{ route('topic.delete', $topic->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" style="background-color: #dc3545">削除</button>
            </form>
            <!-- 編集ボタンの表示 -->
            <a href="{{ route('topic.edit', $topic->id) }}" class="btn btn-primary ml-4">編集</a>
        </div>
        @endif
    </div>

    @empty
    <p>投稿はありません。</p>
    @endforelse

</main>
@vite(['resources/js/jquery.js'])
</body>

@endsection
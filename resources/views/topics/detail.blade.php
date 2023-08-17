@extends('layouts.app')

@section('content')
<div class="container mt-5">
  <h2 class="text-center display-4">{{ $topic->title }}</h2>

  <div class="mb-2 mt-5">
    <p id="content-layout" class="display-5">{{ $topic->content }}</p>
  </div>

  <div class="mb-2">
    @if($topic->imgpath)
    <img src="{{ asset('storage/images/' . $topic->imgpath) }}" alt="投稿画像" class="img-thumbnail mb-3">
    @endif
  </div>

  <!-- いいね機能の実装 -->
  {{-- <form action="{{ route('post.like', $topic->id) }}" method="POST">
  @csrf
  <button type="submit" class="like-button">
    @if($topic->likes()->where('user_id', auth()->id())->exists())
    いいねを取り消す
    @else
    いいね！
    @endif
  </button>
  </form> --}}
  {{-- @if(auth()->check() && $topic->user_id !== auth()->id())
  <button id="like-button" class="btn btn-success mb-3" data-topic-id="{{ $topic->id }}">
  <!--　表示されてる記事のいいねの持つユーザーID（user_id）と現在ログイン中のユーザーID（auth()->id()）が一致するかどうか調べる -->
  @if($topic->likes()->where('user_id', auth()->id())->exists())
  <span class="like-text">いいね!!を取り消す</span>
  @else
  <span class="like-text">いいね!!</span>
  @endif
  <span class="like-count badge badge-light ">{{ $topic->likes()->count() }}</span>
  </button>
  @endif --}}
  @if(auth()->check() && $topic->user_id !== auth()->id())
  <div id="like-button">
    <like-button :topic-id="{{ $topic->id }}" :initial-liked="{{ $topic->likes()->where('user_id', auth()->id())->exists() ? 'true' : 'false' }}" :initial-count="{{ $topic->likes()->count() }}">
    </like-button>
  </div>
  @endif



  <div class="mb-2">
    <label>作成日時:</label>
    <p>{{ $topic->created_at }}</p>
  </div>

  <div class="mb-2">
    <label>更新日時:</label>
    <p>{{ $topic->updated_at }}</p>
  </div>

  <div class="mb-2">
    <label>投稿者:</label>
    <p>{{ $topic->name }}</p>

    <a href="/" class="btn btn-primary mt-4">一覧へ戻る</a>
  </div>
  <!-- @vite(['resources/js/ajax.js']) -->

  @endsection
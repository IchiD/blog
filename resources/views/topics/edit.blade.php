@extends('layouts.app')

@section('content')
@if(Auth::check())
<div class="container mt-5">
  <h2>投稿を編集</h2>

  <form method="POST" action="{{ route('topic.update', $topic->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="form-group">
      <label for="title">タイトル</label>
      <input type="text" class="form-control" id="title" name="title" value="{{ $topic->title }}">
    </div>

    <div class="form-group">
      <label for="content">本文</label>
      <textarea class="form-control" id="content" name="content" rows="4">{{ $topic->content }}</textarea>
    </div>

    <div class="form-group">
      @if($topic->imgpath)
      <img src="{{ asset('storage/images/' . $topic->imgpath) }}" alt="投稿画像" class="img-thumbnail mb-3">
      @endif
      <!-- <label for="img">画像の変更</label> -->
      <input type="file" class="form-control-file" id="img" name="img">
    </div>

    <button type="submit" class="btn btn-primary" style="background-color: #007bff">更新する</button>
  </form>
  <a href="/" class="btn btn-primary mt-4">一覧へ戻る</a>
</div>
@else
return redirect('/')->with('error', 'ログインしてください。');
@endif

@endsection
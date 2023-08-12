<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>ブログ</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>

    <header class="bg-primary">

        @if (Route::has('login'))
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <h1><a class="navbar-brand display-3" style="font-size: 3rem;" href="#">ブログ</a></h1>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    @auth
                    <li class="nav-item">
                        <a class="nav-link text-white">ようこそ、{{ auth()->user()->name }}さん！</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ログアウト</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('login') }}">ログイン</a>
                    </li>
                    @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('register') }}">登録</a>
                    </li>
                    @endif
                    @endauth
                </ul>
            </div>
        </nav>
        @endif



    </header>

    <main class="container">
        <!-- フラッシュメッセージの表示 -->
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- <form method="POST">
            {{ csrf_field() }}
            <input class="form-control my-2" type="text" name="title" placeholder="タイトル">
            <textarea class="form-control my-2" name="content" rows="4" placeholder="本文"></textarea>
            <form action="/upload" enctype="multipart/form-data" method="post">
                @csrf
                <input type="file" name="imgpath">
                <input type="submit" value="アップロードする">
            </form>
            <input class="form-control my-2" type="submit" value="送信">
        </form> -->

        <form method="POST" action="/" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input class="form-control my-2" type="text" name="title" placeholder="タイトル">
            <textarea class="form-control my-2" name="content" rows="4" placeholder="本文" required></textarea>
            <input type="file" name="img">
            <input class="form-control my-2" type="submit" value="送信">
        </form>



        @forelse ( $topics as $topic )
        <div class="border my-2 p-2">
            <div class="p-1">{{ $topic->title }}</div>
            @if ($topic->imgpath)
            <img src="{{ asset('storage/images/' . $topic->imgpath) }}" alt="投稿画像">
            @endif
            <div class="p-2">{!! nl2br(e($topic->content)) !!}</div>
            <div class="text-secondary">投稿者:{{ $topic->name }} さん</div>
            <div class="text-secondary">投稿日:{{ $topic->created_at }}</div>

            <!-- 現在のユーザーのIDと投稿のユーザーIDを比較 -->
            @if(auth()->check() && auth()->id() === $topic->user_id)
            <!-- 削除ボタンの表示 -->
            <form action="{{ route('topic.delete', $topic->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">削除</button>
            </form>
            @endif
        </div>

        @empty
        <p>投稿はありません。</p>
        @endforelse

    </main>
</body>

</html>
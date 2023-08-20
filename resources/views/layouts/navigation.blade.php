<nav class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <h1><a class="navbar-brand display-3" style="font-size: 3rem;" href="/">nブログ</a></h1>

            <div class="flex items-center">
                @if(Auth::check())
                <p class="px-3 py-2">{{ auth()->user()->name }} さん</p>
                @endif
                @if(url()->current() !== url('/'))
                <a href="/" class="px-3 py-2">ホーム</a>
                @endif
                @if(url()->current() !== url('/profile'))
                <a href="{{ route('profile.edit') }}" class="px-3 py-2">プロフィール</a>
                @endif
                @if(Auth::check())
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="px-3 py-2">ログアウト</a>
                </form>
                @else
                @if(url()->current() !== url('/login'))
                <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2">ログイン</a>
                @else
                <a href="{{ route('register') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2">登録</a>
                @endif
                @endif
            </div>
        </div>
    </div>
</nav>
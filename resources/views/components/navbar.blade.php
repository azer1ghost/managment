 <ul class="d-flex justify-content-center align-items-center mb-0">
    @guest
        @if (Route::has('login'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">Login</a>
            </li>
        @endif

        @if (Route::has('register'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('register') }}">Register</a>
            </li>
        @endif
    @else
        <a class="py-1" href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            {{ __('Logout') }}
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    @endguest
{{--    <div class="dropdown">--}}
{{--        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--            <span class="flag-icon flag-icon-{{(session()->get('locale') ?? 'en') == 'en' ? 'gb' : session()->get('locale')}}"></span> {{ucfirst(session()->get('locale') ?? 'en')}}--}}
{{--        </a>--}}
{{--        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">--}}
{{--            @foreach(config('app.locales') as $lang => $language)--}}
{{--                <a class="dropdown-item" href="{{route('locale', $lang)}}"><span class="flag-icon flag-icon-{{$lang == 'en' ? 'gb' : $lang}}"></span> {{$language}}</a>--}}
{{--            @endforeach--}}
{{--        </div>--}}
{{--    </div>--}}
        <livewire:notification :notifications="auth()->user()->notifications()"/>
</ul>

<nav class="navbar navbar-expand-md p-0">
    <button class="navbar-toggler p-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
        <i class="fa fa-bars" style="font-size: 25px;color: #fff"></i>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent" style="background: rgb(5, 68, 104) !important;">
        <ul class="navbar-nav ml-auto">
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
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ auth()->user()->getAttribute('fullname') }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" style="left: -40px !important;min-width: 0;" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item py-1" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            @endguest
            <div class="dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="flag-icon flag-icon-{{(session()->get('locale') ?? 'en') == 'en' ? 'gb' : session()->get('locale')}}"></span> {{ucfirst(session()->get('locale') ?? 'en')}}
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    @foreach(config('app.locales') as $lang => $language)
                        <a class="dropdown-item" href="{{route('locale', $lang)}}"><span class="flag-icon flag-icon-{{$lang == 'en' ? 'gb' : $lang}}"></span> {{$language}}</a>
                    @endforeach
                </div>
            </div>
        </ul>
    </div>
</nav>
@extends('layouts.main')

@section('content')
<main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
    <div class="container">
        <div class="card login-card">
            <div class="row no-gutters">
                <div class="col-md-5">
                    <img src="https://source.unsplash.com/900x1100/?buldings" id="loginBackground" alt="login" class="login-card-img">
                </div>
                <div class="col-md-7">
                    <div class="card-body">
                        <div class="brand-wrapper">
                            <img src="{{asset('assets/images/logos/group.png')}}" alt="logo" class="logo">
                        </div>
                        <p class="login-card-description">Sign into your account</p>
                        @if($errors->any())
                            {!! implode('', $errors->all('<div class="text-danger">:message</div>')) !!}
                        @endif
                        <form method="POST" class="form-row" action="{{ route('login') }}">
                            @csrf
                            @env('local')
                                <x-input::email required="" value="test@mobilgroup.az" name="login" label="Email" />
                                <x-input::text type="password" required="" value="Aa123456" name="password"/>
                                @else
                                <x-input::email required="" name="login" label="Email" />
                                <x-input::text type="password" required="" name="password"/>
                            @endenv
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-block login-btn mb-4" type="submit">Login</button>
                        </form>
                        @if (Route::has('password.request'))
                            <a class="forgot-password-link" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                        <p class="login-card-footer-text">Don't have an account? <a href="{{route('register')}}" class="text-reset">Register here</a></p>
{{--                        <nav class="login-card-footer-nav">--}}
{{--                            <a href="#!">Terms of use.</a>--}}
{{--                            <a href="#!">Privacy policy</a>--}}
{{--                        </nav>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection

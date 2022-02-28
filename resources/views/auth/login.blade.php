@extends('layouts.main')

@section('title', __('translates.login.login'))

@section('style')
    <style>
        .custom-wrapper main {
            width: 100% !important;
            margin-left: 0 !important;
        }
    </style>
@endsection

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
                        <p class="login-card-description">@lang('')</p>
                        @if($errors->any())
                            {!! implode('', $errors->all('<div class="text-danger">:message</div>')) !!}
                        @endif
                        <form method="POST" class="form-row" action="{{ route('login') }}">
                            @if(now()->format('m/d/Y') < date_create("03/07/2022")->format('m/d/Y'))
                            <div class="alert alert-danger">Diqqət, Mobil Managementdəki dəyişikliyə görə bütün hesablardan çıxış olunub. Şifrənizi unutmusunuzsa aşağıdakı "Şifrəmi Bərpa Et" düyməsinə basın</div>
                            @endif
                            @csrf
                            @env('local')
                                <x-input::email required="" value="test@mobilgroup.az" name="login" :label="__('translates.register.mail')" :placeholder="__('translates.placeholders.mail')" />
                                <x-input::text type="password" required="" value="Aa123456" name="password" :label="__('translates.register.password')" :placeholder="__('translates.placeholders.password')"/>
                                @else
                                <x-input::email required="" name="login" label="Email" :label="__('translates.register.mail_coop')" :placeholder="__('translates.placeholders.mail_coop')"/>
                                <x-input::text type="password" required="" name="password" :label="__('translates.register.password')" :placeholder="__('translates.placeholders.password')"/>
                            @endenv
                            <div class="form-group">
                                <div class="col-md-12">
                                    <div>
                                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                        <label class="form-check-label" for="remember">
                                            @lang('translates.login.remember')
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-block login-btn mb-4" type="submit">@lang('translates.login.login')</button>
                        </form>
                        @if (Route::has('password.request'))
                            <a class="forgot-password-link" href="{{ route('password.request') }}">
                                <button class="btn btn-block login-btn mb-4" type="button">@lang('translates.login.forgot_pwd')</button>
                            </a>
                        @endif
                        <p class="login-card-footer-text">@lang('translates.login.no_account') <a href="{{route('register')}}" class="text-reset">@lang('translates.login.register_here')</a></p>
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

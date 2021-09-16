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
                            <p class="login-card-description">Hesab aktivləşdirmə</p>
                            @if (session('resent'))
                                <div class="alert alert-success" role="alert">
                                    Yeni gizli kodunuz nömrənizə göndərildi. Nömrənizin düzgün olduğundan əmin olun.
                                </div>
                            @endif
                            <p>Salam, {{auth()->user()->getAttribute('fullname')}}. Sizə sms vasitəsi ilə gizli kod göndərdik.</p>
                            <p>Nömrəniz: {{auth()->user()->getAttribute('phone')}}. Əgər nömrəniz səhvdirsə, <button data-toggle="modal" data-target="#change-phone" class="btn btn-link p-0 m-0 align-baseline" style="text-decoration: underline !important;">buranadan</button> dəyişdirə bilərsiniz.</p>
                            <div class="modal fade" id="change-phone" tabindex="-1" aria-labelledby="change-phone" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Nömrəni dəyişdir</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" class="my-3" action="{{route('phone.update')}}">
                                                @csrf
                                                <label for="">Nömrəniz</label>
                                                <div class="input-group mb-3">
                                                    <input type="text" name="phone" pattern="[+][0-9]{3} [0-9]{2}-[0-9]{3}-[0-9]{2}-[0-9]{2}" size="17" required value="{{auth()->user()->getAttribute('phone')}}" class="form-control mb-0 @error('phone') is-invalid @enderror" autocomplete="off">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-outline-secondary" type="submit">Dəyişdir</button>
                                                    </div>
                                                </div>
                                                @error('phone')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-3 d-flex justify-content-center align-items-center">
                                <div class="col-8">
                                    <form method="POST" action="{{route('phone.verification.verify')}}">
                                        @csrf
                                        <div class="input-group mb-3">
                                            <input name="code" required pattern="[0-9]+" maxlength="6" minlength="6" type="text" class="form-control mb-0 @error('code') is-invalid @enderror" placeholder="SMS doğrulama kodu" autocomplete="off">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="submit">Doğrula</button>
                                            </div>
                                        </div>
                                        @error('code')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                        @enderror
                                    </form>
                                </div>
                            </div>

                            Əgər kodu almadınızsa yenidən göndərin.
                            <form class="d-inline" method="POST" action="{{ route('phone.verification.resend') }}">
                                @csrf
                                <button disabled id="resend-code" type="submit" class="btn btn-outline-primary m-0 "><span id="timer"></span></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            let timer = 60;

            const resendCode = $('#resend-code');

            const interval = setInterval(function (){
                if(timer === 1) return stop();
                timer -= 1;
                document.getElementById('timer').innerHTML = timer + ' saniyə sonra yenidən göndər';
            }, 1000);

            function stop() {
                clearInterval(interval);
                document.getElementById('timer').innerHTML = "Yenidən göndər";
                resendCode.attr('disabled', false);
            }

            document.getElementById('timer').innerHTML = timer  + ' saniyə sonra yenidən göndər';
        });

    </script>

@endsection

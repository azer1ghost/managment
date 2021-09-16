@extends('layouts.main')

@section('style')
    <style>
        .custom-wrapper main {
            width: 100% !important;
            margin-left: 0 !important;
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Hesab aktivləşdirmə</div>
                <div class="card-body">
                    @php($phone = auth()->user()->getAttribute('phone'))
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            Yeni gizli kodunuz nömrənizə göndərildi. Nömrənizin düzgün olduğundan əmin olun.
                        </div>
                    @endif
                    Salam, {{auth()->user()->getAttribute('fullname')}}. Sizə sms vasitəsi ilə gizli kod göndərdik.
                    Nömrəniz: {{$phone}}.

                    <div class="row m-3 d-flex justify-content-center">
                        <div class="col-6">
                            <form method="POST" action="{{route('phone.verification.verify')}}">
                                @csrf
                                <div class="input-group mb-3">
                                    <input name="code" required pattern="[0-9]+" maxlength="6" minlength="6" type="text" class="form-control @error('code') is-invalid @enderror" placeholder="SMS doğrulama kodu">
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
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">Yenidən göndər</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

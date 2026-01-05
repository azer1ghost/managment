@extends('pages.transit.layout')

@section('title', __('transit.title') . ' | Email Doğrulama')

@section('content')
<div class="transit-card">
    <div class="p-4">
        <div class="text-center mb-4">
            <div class="mb-3">
                <i class="fas fa-envelope fa-5x text-primary pulse-animation" style="filter: drop-shadow(0 5px 20px rgba(102, 126, 234, 0.5));"></i>
            </div>
            <h3 style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800;">
                Hesab aktivləşdirmə
            </h3>
        </div>

        @if (session('resent'))
            <div class="alert alert-success" role="alert" style="border-radius: 15px; border-left: 5px solid #28a745;">
                <i class="fas fa-check-circle"></i>
                Yeni doğrulama kodu email ünvanınıza göndərildi. Email ünvanınızın düzgün olduğundan əmin olun.
            </div>
        @endif

        <p class="text-center mb-3">Salam, <strong>{{auth('transit')->user()->getAttribute('name')}}</strong>. Sizə email vasitəsi ilə doğrulama kodu göndərdik.</p>
        <p class="text-center mb-4">Email ünvanınız: <strong>{{auth('transit')->user()->getAttribute('email')}}</strong></p>

        <div class="row mt-3 d-flex justify-content-center align-items-center">
            <div class="col-12">
                <form method="POST" action="{{route('email.verification.verify')}}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold" for="code" style="font-size: 16px;">
                            <i class="fas fa-key text-primary me-2"></i> Doğrulama kodu
                        </label>
                        <div class="input-group-icon">
                            <input name="code" id="code" required pattern="[0-9]+" maxlength="6" minlength="6" 
                                   type="text" class="form-control @error('code') is-invalid @enderror" 
                                   placeholder="6 rəqəmli doğrulama kodu" autocomplete="off"
                                   style="padding-left: 50px;">
                            <i class="fas fa-key input-icon"></i>
                        </div>
                        @error('code')
                        <div class="alert alert-danger mt-2" style="border-radius: 10px;">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-check"></i> Doğrula
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted mb-2">Əgər kodu almadınızsa yenidən göndərin.</p>
            <form class="d-inline" method="POST" action="{{ route('email.verification.resend') }}">
                @csrf
                <button disabled id="resend-code" type="submit" class="btn btn-outline-primary">
                    <span id="timer">60 saniyə sonra yenidən göndər</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('style')
<style>
    .input-group-icon {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #667eea;
        z-index: 5;
        transition: all 0.3s ease;
    }

    .form-control:focus ~ .input-icon {
        color: #764ba2;
        transform: translateY(-50%) scale(1.2);
    }
</style>
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

        document.getElementById('timer').innerHTML = timer + ' saniyə sonra yenidən göndər';
    });
</script>
@endsection


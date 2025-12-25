@extends('pages.transit.layout')

@section('title', __('transit.title') . ' | ' . __('transit.payment'))

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="transit-card">
            <div class="p-4">
                <div class="text-center mb-5">
                    <div class="mb-3">
                        <i class="fas fa-credit-card fa-5x text-primary pulse-animation" 
                           style="filter: drop-shadow(0 5px 20px rgba(102, 126, 234, 0.5)); 
                                  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                  -webkit-background-clip: text;
                                  -webkit-text-fill-color: transparent;"></i>
                    </div>
                    <h3 class="mb-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                                           -webkit-background-clip: text; 
                                           -webkit-text-fill-color: transparent; 
                                           font-weight: 800; 
                                           font-size: 2.5rem;">
                        {{ __('transit.payment.order_payment') }}
                    </h3>
                    <p class="text-muted fs-5">{{ __('transit.message.upload_required') }}</p>
                </div>

                <div class="order-summary mb-4 p-4 rounded" 
                     style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
                            border: 2px solid rgba(102, 126, 234, 0.2);
                            position: relative;
                            overflow: hidden;">
                    <div style="position: absolute; top: -50%; right: -50%; width: 200%; height: 200%; 
                                background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
                                animation: rotate 20s linear infinite;"></div>
                    <h5 class="mb-4" style="position: relative; z-index: 1;">
                        <i class="fas fa-receipt text-primary me-2"></i> 
                        <span style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                                    -webkit-background-clip: text; 
                                    -webkit-text-fill-color: transparent; 
                                    font-weight: 700;">
                            {{ __('transit.payment.order_summary') }}
                        </span>
                    </h5>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <i class="fas fa-cog text-muted"></i>
                            <span class="text-muted">{{ __('transit.profile.service') }}</span>
                        </div>
                        <strong>{{$order->getAttribute('service')}}</strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <i class="fas fa-hashtag text-muted"></i>
                            <span class="text-muted">{{ __('transit.payment.order_number') }}</span>
                        </div>
                        <strong class="text-primary">{{$order->getAttribute('code')}}</strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <i class="fas fa-money-bill text-muted"></i>
                            <span class="text-muted">{{ __('transit.payment.amount') }}</span>
                        </div>
                        <strong>{{number_format($order->getAttribute('amount'), 2)}} AZN</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center" style="position: relative; z-index: 1;">
                        <h5 class="mb-0" style="font-weight: 700;">{{ __('transit.payment.total_amount') }}</h5>
                        <h3 class="mb-0 pulse-animation" 
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                                   -webkit-background-clip: text; 
                                   -webkit-text-fill-color: transparent; 
                                   font-weight: 900;
                                   text-shadow: 0 0 30px rgba(102, 126, 234, 0.3);">
                            <strong>{{number_format($order->getAttribute('amount'), 2)}} AZN</strong>
                        </h3>
                    </div>
                </div>

                <div class="payment-methods mb-4">
                    <h5 class="mb-3"><i class="fas fa-wallet text-primary"></i> Payment Methods</h5>
                    
                    {{-- Uncomment when payment integration is ready --}}
                    {{-- <form action="{{ route('payFromBalance') }}" method="POST" id="paymentForm">
                        @csrf
                        <input type="hidden" name="code" value="{{$order->getAttribute('code')}}">
                        
                        <div class="d-grid gap-2 mb-3">
                            <button type="button" class="btn btn-primary btn-lg payment-method-btn" data-method="card">
                                <i class="fas fa-credit-card"></i> Pay by Card
                            </button>
                        </div>

                        @if(auth()->user()->getAttribute('balance') >= $order->getAttribute('amount'))
                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-success btn-lg payment-method-btn" data-method="balance">
                                    <i class="fas fa-wallet"></i> Pay from Balance
                                    <small class="d-block mt-1">Available: {{number_format(auth()->user()->getAttribute('balance'), 2)}} AZN</small>
                                </button>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> 
                                Insufficient balance. Current balance: {{number_format(auth()->user()->getAttribute('balance'), 2)}} AZN
                            </div>
                        @endif
                    </form> --}}

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Payment integration is being configured. Please contact us for payment.
                    </div>
                </div>

                <div class="contact-info p-4 rounded mb-4 glow" 
                     style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                            color: white;
                            position: relative;
                            overflow: hidden;">
                    <div style="position: absolute; top: -50%; right: -50%; width: 200%; height: 200%; 
                                background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
                                animation: rotate 15s linear infinite;"></div>
                    <h5 class="mb-3" style="position: relative; z-index: 1;">
                        <i class="fas fa-headset me-2"></i> {{ __('transit.payment.need_help') }}
                    </h5>
                    <div class="row text-center" style="position: relative; z-index: 1;">
                        <div class="col-md-4 mb-3">
                            <a href="tel:+994513339090" class="text-white text-decoration-none contact-item" 
                               style="display: block; padding: 15px; border-radius: 15px; transition: all 0.3s ease;">
                                <i class="fas fa-phone fa-2x mb-2 pulse-animation"></i>
                                <div style="font-weight: 600;">+994 51 333 90 90</div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="mailto:info@mobilbroker.az" class="text-white text-decoration-none contact-item"
                               style="display: block; padding: 15px; border-radius: 15px; transition: all 0.3s ease;">
                                <i class="fas fa-envelope fa-2x mb-2 pulse-animation"></i>
                                <div style="font-weight: 600;">info@mobilbroker.az</div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="tel:9090" class="text-white text-decoration-none contact-item"
                               style="display: block; padding: 15px; border-radius: 15px; transition: all 0.3s ease;">
                                <i class="fas fa-phone-office fa-2x mb-2 pulse-animation"></i>
                                <div style="font-weight: 600;">*9090</div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{route('client-account')}}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i> {{ __('transit.button.back') }} {{ __('transit.nav.home') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Payment method selection
    $('.payment-method-btn').on('click', function() {
        const method = $(this).data('method');
        
        if (method === 'card') {
            // Redirect to card payment gateway
            // window.location.href = '{{-- payment gateway URL --}}';
            alert('Card payment integration coming soon!');
        } else if (method === 'balance') {
            // Submit form for balance payment
            if (confirm('Are you sure you want to pay from your balance?')) {
                $('#paymentForm').submit();
            }
        }
    });
});
</script>

<style>
.order-summary {
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}
.payment-method-btn {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}
.payment-method-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}
.payment-method-btn:active {
    transform: translateY(0);
}
.contact-item {
    transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}
.contact-item:hover {
    background: rgba(255,255,255,0.2) !important;
    transform: translateY(-10px) scale(1.1);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

@keyframes rotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endsection

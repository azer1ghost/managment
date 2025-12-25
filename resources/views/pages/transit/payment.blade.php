@extends('pages.transit.layout')

@section('title', 'Online Transit | Payment')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="transit-card">
            <div class="p-4">
                <div class="text-center mb-4">
                    <i class="fas fa-credit-card fa-3x text-primary mb-3"></i>
                    <h3 class="mb-2">Order Payment</h3>
                    <p class="text-muted">Complete your payment to proceed</p>
                </div>

                <div class="order-summary mb-4 p-4 rounded" style="background: #f8f9fa;">
                    <h5 class="mb-4"><i class="fas fa-receipt text-primary"></i> Order Summary</h5>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <i class="fas fa-cog text-muted"></i>
                            <span class="text-muted">Service</span>
                        </div>
                        <strong>{{$order->getAttribute('service')}}</strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <i class="fas fa-hashtag text-muted"></i>
                            <span class="text-muted">Order Number</span>
                        </div>
                        <strong class="text-primary">{{$order->getAttribute('code')}}</strong>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <i class="fas fa-money-bill text-muted"></i>
                            <span class="text-muted">Amount</span>
                        </div>
                        <strong>{{number_format($order->getAttribute('amount'), 2)}} AZN</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Total Amount</h5>
                        <h4 class="mb-0 text-primary">
                            <strong>{{number_format($order->getAttribute('amount'), 2)}} AZN</strong>
                        </h4>
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

                <div class="contact-info p-4 rounded mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h5 class="mb-3"><i class="fas fa-headset"></i> Need Help?</h5>
                    <div class="row text-center">
                        <div class="col-md-4 mb-3">
                            <a href="tel:+994513339090" class="text-white text-decoration-none">
                                <i class="fas fa-phone fa-2x mb-2"></i>
                                <div>+994 51 333 90 90</div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="mailto:info@mobilbroker.az" class="text-white text-decoration-none">
                                <i class="fas fa-envelope fa-2x mb-2"></i>
                                <div>info@mobilbroker.az</div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="tel:9090" class="text-white text-decoration-none">
                                <i class="fas fa-phone-office fa-2x mb-2"></i>
                                <div>*9090</div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{route('client-account')}}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i> Back to Homepage
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
.contact-info a {
    transition: all 0.3s ease;
}
.contact-info a:hover {
    opacity: 0.8;
    transform: translateY(-3px);
}
</style>
@endsection

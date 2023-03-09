@extends('pages.transit.layout')

@section('title', 'Online Transit | Payment')

@section('content')
    <div class="col-12 p-lg-5 py-4">
        <div class="card position-sticky top-0">
            <div class="p-3">
                <h6 class="card-title mb-3">Sifariş Ödənişi</h6>
                <div class="d-flex justify-content-between mb-1 ">
                    <span>Xidmət</span> <span>{{$order->getAttribute('service')}}</span>
                </div>
                <div class="d-flex justify-content-between mb-1 ">
                    <span>Qiymət</span> <span>{{$order->getAttribute('amount')}} AZN</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-4 ">
                    <span>Toplam Qiymət</span> <strong class="text-dark">{{$order->getAttribute('amount')}} AZN</strong>
                </div>

                <form action="{{ route('payFromBalance') }}" method="POST">
                    @csrf
                    <input type="hidden" name="code" value="{{$order->getAttribute('code')}}">
                    <div class="form-outline mb-4">
                        <a type="button" class="btn btn-primary col-12">Kart ilə ödə</a>
                    </div>
                    @if(auth()->user()->getAttribute('balance') > $order->getAttribute('amount'))
                        <div class="form-outline mb-4">
                            <button type="submit" class="btn btn-success col-12">Avans</button>
                        </div>
                    @endif
                    <button type="submit" class="btn btn-danger btn-block mb-3"><a href="{{route('service')}}">Ana
                            Səhifəyə qayıt</a>
                    </button>
                </form>
                <div class="text-center p-3">
                    <a class="text-dark mx-2" href="tel:+994513339090"><i
                                class="fas fa-phone mr-1"></i>+994513339090</a>
                    <a class="text-dark mx-2" href="mailto:info@mobilbroker.az"><i class="fas fa-envelope mr-1"></i>info@mobilbroker.az</a>
                    <a class="text-dark mx-2" href="tel:9090"><i class="fas fa-phone-office mr-1"></i> *9090</a>
                </div>

            </div>
        </div>
    </div>

@endsection
@section('scripts')

@endsection
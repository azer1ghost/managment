@extends('pages.transit.layout')

@section('title', __('translates.navbar.transit'))

@section('content')
                <div class="col-12 p-5">
                    <form>
                        <div class="form-outline mb-4">
                            <button type="button" class="btn btn-warning col-12">Kart ilə ödə</button>
                        </div>

                        <div class="form-outline mb-4">
                            <button type="button" class="btn btn-warning col-12">Avans</button>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block mb-3">İstəyiniz Qeydə Alındı, sizinlə əlaqə
                            saxlanılacaq
                        </button>
                        <button type="submit" class="btn btn-primary btn-block mb-3"><a href="{{route('service')}}">Ana Səhifəyə qayıt</a>
                        </button>
                        <label class="form-label">Bizi Seçdiyiniz üçün təşəkkür edirik!</label>
                    </form>
                    <footer>*9090 +994513339090 info@mobilbroker.az</footer>
                </div>

@endsection
@section('scripts')

@endsection
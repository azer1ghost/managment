@extends('pages.transit.layout')

@section('title', 'Online Transit | Account')


@section('content')
    <div class="row gutters-sm my-5">
        <div class="col-md-4 mb-5">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="{{asset('assets/images/diamond-green.png')}}" alt="Admin" class="rounded-circle"
                             width="150">
                        <div class="mt-4">
                            <h4>{{auth()->user()->getFullnameAttribute()}}</h4>
                            <p class="text-secondary mb-1">{{auth()->user()->getAttribute('voen')}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link text-black" href="{{ route('service') }}">Ana Səhifə</a>
                    <a class="nav-link text-black" id="tab-account" data-toggle="tab" href="#pills-account" role="tab"
                       aria-controls="pills-account" aria-selected="true">Hesab</a>
                    <a class="nav-link text-black" id="tab-balance" data-toggle="tab" href="#pills-balance" role="tab"
                       aria-controls="pills-balance" aria-selected="true">Balans</a>
                    <a class="nav-link text-black" id="tab-order" data-toggle="tab" href="#pills-order" role="tab"
                       aria-controls="pills-order" aria-selected="false">Sifarişlərim</a>
                    <a class="nav-link text-black" id="tab-transactions" data-toggle="tab" href="#pills-transactions"
                       role="tab"
                       aria-controls="pills-transactions" aria-selected="false">Tranzaksiyalar</a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="pills-account" role="tabpanel"
                             aria-labelledby="tab-account">

                            <div class="row my-2">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Full Name</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{auth()->user()->getFullnameAttribute()}}
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Email</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{auth()->user()->getAttribute('email')}}
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Voen</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{auth()->user()->getAttribute('voen')}}
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Phone Number</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{auth()->user()->getAttribute('phone')}}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                    <a class="btn btn-info" href="{{route('profile.edit' , auth()->id())}}">Edit</a>
                                    <a class="btn btn-danger" href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>

                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-balance" role="tabpanel"
                             aria-labelledby="tab-balance">

                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Balans</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    {{auth()->user()->getAttribute('balance')}}
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                    <a class="btn btn-info" href="{{route('profile.edit' , auth()->id())}}">Artır</a>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-order" role="tabpanel"
                             aria-labelledby="tab-order">

                            <div class="row">
                                <table class="table table-responsive-md">
                                    <thead>
                                    <tr>
                                        <th>Nömrə</th>
                                        <th>Xidmət</th>
                                        <th>Tarix</th>
                                        <th>Status</th>
                                        <th>Nəticə</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($orders as $order)
                                        <tr>
                                            <td>{{$order->getAttribute('code')}}</td>
                                            <td>{{$order->getAttribute('service')}}</td>
                                            <td>{{$order->getAttribute('created_at')}}</td>
                                            <td>{{trans('translates.orders.statuses.'.$order->getAttribute('status'))}}</td>
                                            <td>
                                                @if($order->getAttribute('result') !== null)
                                                    <a class="text-black" href="{{route('order-result.download', $order)}}">
                                                        <i style="font-size: 35px" class="fas fa-file"></i>
                                                    </a>
                                                @endif
                                            </td>
                                            @empty
                                                <td colspan="8" class="alert alert-primary text-center">Sifarişiniz
                                                    yoxdur
                                                </td>
                                        </tr>

                                    @endforelse
                                    </tbody>
                                </table>
                                {{$orders->appends(request()->input())->links()}}
                            </div>
                            <hr>
                        </div>

                        <div class="tab-pane fade" id="pills-transactions" role="tabpanel">
                            <div class="row">
                                <table class="table table-responsive-md">
                                    <thead>
                                    <tr>
                                        <th>Nömrə</th>
                                        <th>Növ</th>
                                        <th>Tarix</th>
                                        <th>Dəyər</th>
                                        <th>Nəticə</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>TRN48745684</td>
                                        <td>Balans Artımı</td>
                                        <td>29.01.2023 15:45</td>
                                        <td>45 AZN</td>
                                        <td>Ödənildi</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <hr>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
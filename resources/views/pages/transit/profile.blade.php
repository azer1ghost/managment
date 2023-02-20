@extends('pages.transit.layout')

@section('title', __('translates.navbar.transit'))


@section('content')
    <div class="row gutters-sm my-5">
        <div class="col-md-4 mb-5">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-column align-items-center text-center">
                        <img src="{{asset('assets/images/diamond-green.png')}}" alt="Admin" class="rounded-circle"
                             width="150">
                        <div class="mt-4">
                            <h4>John Doe</h4>
                            <p class="text-secondary mb-1">Full Stack Developer</p>
                            <p class="text-muted font-size-sm">Bay Area, San Francisco, CA</p>
                            <a class="btn btn-outline-primary">Hesabdan çıx</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link text-black" id="tab-account" data-toggle="tab" href="#pills-account" role="tab"
                       aria-controls="pills-account" aria-selected="true">Hesab</a>
                    <a class="nav-link text-black" id="tab-balance" data-toggle="tab" href="#pills-balance" role="tab"
                       aria-controls="pills-balance" aria-selected="true">Balans</a>
                    <a class="nav-link text-black" id="tab-order" data-toggle="tab" href="#pills-order" role="tab"
                       aria-controls="pills-order" aria-selected="false">Sifarişlərim</a>
                    <a class="nav-link text-black" id="tab-transactions" data-toggle="tab" href="#pills-transactions" role="tab"
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

                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Full Name</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    Kenneth Valdez
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                    <a class="btn btn-info" href="{{route('profile.edit' , auth()->id())}}">Edit</a>
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
                                    Kenneth Valdez
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                    <a class="btn btn-info" href="{{route('profile.edit' , auth()->id())}}">Edit</a>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="pills-order" role="tabpanel"
                             aria-labelledby="tab-order">

                            <div class="row">
                                <div class="col-sm-3">
                                    <h6 class="mb-0">Sifarişlərim</h6>
                                </div>
                                <div class="col-sm-9 text-secondary">
                                    Kenneth Valdez
                                </div>
                            </div>

                            <hr>
                            <div class="row">
                                <div class="col-sm-12">
                                    <a class="btn btn-info" href="{{route('profile.edit' , auth()->id())}}">Edit</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('scripts')
@endsection
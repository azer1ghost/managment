@extends('layouts.main')

@section('title', __('translates.navbar.bonus'))

@section('style')
    <style>
        .card {
            background-color: #fff;
            border-radius: 10px;
            border: none;
            position: relative;
            margin-bottom: 30px;
            box-shadow: 0 0.46875rem 2.1875rem rgba(90,97,105,0.1), 0 0.9375rem 1.40625rem rgba(90,97,105,0.1), 0 0.25rem 0.53125rem rgba(90,97,105,0.12), 0 0.125rem 0.1875rem rgba(90,97,105,0.1);
        }
        .l-bg-cherry {
            background: linear-gradient(to right, #493240, #f09) !important;
            color: #fff;
        }
        .l-bg-blue-dark {
            background: linear-gradient(to right, #373b44, #4286f4) !important;
            color: #fff;
        }
        .l-bg-blue-greenyellow {
            background: linear-gradient(to right, #373b44, #9dc827) !important;
            color: #fff;
        }
        .l-bg-green-dark {
            background: linear-gradient(to right, #0a504a, #38ef7d) !important;
            color: #fff;
        }
        .l-bg-orange-dark {
            background: linear-gradient(to right, #a86008, #ffba56) !important;
            color: #fff;
        }
        .l-bg-orange-red {
            background: linear-gradient(to right, #a86008, #e3342f) !important;
            color: #fff;
        }
        .l-bg-blue-purple {
            background: linear-gradient(to right, #3490dc, #bc79ea) !important;
            color: #fff;
        }
        .l-bg-blue-green {
            background: linear-gradient(to right, #3490dc, #83e08b) !important;
            color: #fff;
        }
        .card .card-statistic-3 .card-icon-large .fas, .card .card-statistic-3 .card-icon-large .far, .card .card-statistic-3 .card-icon-large .fab, .card .card-statistic-3 .card-icon-large .fal {
            font-size: 110px;
        }
        .card .card-statistic-3 .card-icon {
            text-align: center;
            line-height: 50px;
            margin-left: 15px;
            color: #000;
            position: absolute;
            right: 3px;
            top: 20px;
            opacity: 0.1;
        }
        .l-bg-cyan {
            background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
            color: #fff;
        }
        .l-bg-green {
            background: linear-gradient(135deg, #23bdb8 0%, #43e794 100%) !important;
            color: #fff;
        }
        .l-bg-orange {
            background: linear-gradient(to right, #f9900e, #ffba56) !important;
            color: #fff;
        }
    </style>
@endsection

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.bonus')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <div class="row mb-2">
        <div class="col-xl-5 col-lg-6">
            <div class="card widget l-bg-cyan">
                <div class="card-statistic-3 p-4">
                    <div class="row align-items-center mb-2 d-flex">
                        <form action="{{route('bonuses.create-referral-link')}}" method="POST">
                            @csrf
                            <label for="basic-url">Your Referral link</label>
                            @if(true)
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3">https://mobex.az/register?ref=elvinaqalarov</span>
                                    </div>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-primary">
                                            <i class="fal fa-copy"></i>
                                            Copy
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon3">https://mobex.az/register?ref=</span>
                                    </div>
                                    <input type="text" minlength="6" maxlength="15" class="form-control" name="referral">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-outline-primary">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            @endif
                            <small class="form-text text-light">This is your referral link, copy and share with your friends</small>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-6">
            <div class="card widget l-bg-blue-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Total Referrals</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                                0
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-lg-6">
            <div class="card widget l-bg-green-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-dollar-sign"></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Bonus balance</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                                0
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card widget l-bg-blue-purple">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas far fa-gift"></i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Efficiency</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                                10 (20%)
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 text-center mb-3">
            <p class="text-muted m-3">For getting referral data
                <a href="#" class="btn btn-link" onclick="event.preventDefault(); document.getElementById('get-referral-data').submit();">Send request <i class="ml-1 fal fa-smile"></i></a>
            </p>

            <form id="get-referral-data" action="{{ route('bonuses') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
        <div class="col-12">
            <table class="table table-hover table-sm--responsive">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">First</th>
                    <th scope="col">Last</th>
                    <th scope="col">Handle</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>@mdo</td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                    <td>@fat</td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td colspan="2">Larry the Bird</td>
                    <td>@twitter</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
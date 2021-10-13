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
        .card .card-statistic-3 .card-icon-large i{
            font-size: 110px;
            font-style: normal;
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
                    <form action="{{route('bonuses.generate-referral-link')}}" method="POST">
                        @csrf
                        <label for="basic-url">Your Referral link</label>
                        @if($referral->isReal())
                            <div class="input-group">
                                <input class="form-control" id="referral-key" value="https://mobex.az/register?ref={{$referral->getAttribute('key')}}" readonly>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" id="referral-copy">
                                        <i class="fal fa-copy"></i>
                                        <span>Copy</span>
                                    </button>
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="user_id" value="{{auth()->id()}}">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3">https://mobex.az/register?ref=</span>
                                </div>
                                <input type="text" required minlength="6" maxlength="15" class="form-control" name="key">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">
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
        <div class="col-xl-3 col-lg-6">
            <div class="card widget l-bg-blue-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                    <div class="mb-3">
                        <h5 class="card-title mb-0">Total Referrals</h5>
                    </div>
                    @php($efficiency = $referral->getAttribute('efficiency') ?? 0)
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                                {{$referral->getAttribute('total') ?? 0}}
                            </h2>
                        </div>
                        <div class="col-4 text-right">
                            <span>{{$efficiency}}% <i class="fa fa-arrow-up"></i></span>
                        </div>
                    </div>
                    <div class="progress mt-1 " data-height="8" style="height: 8px;">
                        <div class="progress-bar l-bg-cyan" role="progressbar" data-width="{{$efficiency}}%" aria-valuenow="{{$efficiency}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$efficiency}}%;z-index: 0"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-6">
            <div class="card widget l-bg-blue-purple">
                <div class="card-statistic-3 px-4 pt-3" style="padding-bottom: 7px">
                    <div class="card-icon card-icon-large"><i class="far fa-vial"></i></div>
                    <div class="mb-0">
                        <h5 class="card-title mb-1">Packages</h5>
                    </div>
                    <div class="row align-items-center mb-1 d-flex">
                        <div class="col-8">
                            <h3 class="d-flex align-items-center mb-0">
                                {{$referral->getAttribute('total_packages') ?? 0}}
                            </h3>
                        </div>
                    </div>
                    <div class="mb-0">
                        <h5 class="card-title mb-1">Earnings</h5>
                    </div>
                    <div class="row align-items-center d-flex">
                        <div class="col-8">
                            <h3 class="d-flex align-items-center mb-0">
                                {{$referral->getAttribute('total_earnings') ?? 0}} ₼
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-lg-6">
            <div class="card widget l-bg-green-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fa">₼</i></div>
                    <div class="mb-4">
                        <h5 class="card-title mb-0">Bonus balance</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-8">
                            <h2 class="d-flex align-items-center mb-0">
                                {{$referral->getAttribute('bonus') ?? 0}} ₼
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($referral->isReal())
            <div class="col-12 text-center mb-3">
                <p class="text-muted m-3">For getting referral data
                    <a href="#" class="btn btn-link py-0" onclick="event.preventDefault(); document.getElementById('get-referral-data').submit();">Send request <i class="ml-1 fal fa-smile"></i></a>
                    @if (!$referral->isNew()) (Last updated: {{optional($referral->getAttribute('updated_at'))->diffForHumans()}})@endif
                </p>
                <form id="get-referral-data" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        @endif
        <div class="col-12">
            <table class="table table-hover table-sm--responsive">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Fullname</th>
                    <th scope="col">MBX code</th>
                    <th scope="col">Delivered packages</th>
                    <th scope="col">Total earnings</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                    <td>Otto</td>
                    <td>Otto</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('#referral-copy').click(function(){
            const link = $('#referral-key');
            navigator.clipboard.writeText(link.val());
            link.addClass('is-valid');
            link.parent().addClass('is-valid');
            $(this).children('span').text('Copied');
        });
    </script>
@endsection
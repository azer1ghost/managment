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
        .l-bg-blue-dark {
            background: linear-gradient(to right, #373b44, #4286f4) !important;
            color: #fff;
        }
        .l-bg-green-dark {
            background: linear-gradient(to right, #0a504a, #38ef7d) !important;
            color: #fff;
        }
        .l-bg-blue-purple {
            background: linear-gradient(to right, #3490dc, #bc79ea) !important;
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
        @media only screen and (max-width: 600px) {
            .input-group-text {
                padding: 0;
                font-size: .75rem;
            }
        }









        section {
            width: 90%;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        section + section {
            margin-top: 2.5em;
        }

        h1 {
            font-weight: 700;
            line-height: 1.125;
            font-size: clamp(1.5rem, 2.5vw, 2.5rem);
        }

        h2 {
            margin-top: 0.25em;
            color: #999;
            font-size: clamp(1.125rem, 2.5vw, 1.25rem);
        }
        h2 + * {
            margin-top: 1.5em;
        }

        summary {
            background-color: #fff;
            position: relative;
            cursor: pointer;
            padding: 1em 0.5em;
            list-style: none;
        }
        summary::-webkit-details-marker {
            display: none;
        }
        summary:hover {
            background-color: #f2f5f9;
        }
        summary div {
            display: flex;
            align-items: center;
        }
        summary h3 {
            display: flex;
            flex-direction: column;
        }
        summary small {
            color: #999;
            font-size: 0.875em;
        }
        summary strong {
            font-weight: 700;
        }
        summary span:first-child {
            width: 4rem;
            height: 4rem;
            border-radius: 10px;
            background-color: #f3e1e1;
            display: flex;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            margin-right: 1.25em;
        }
        summary span:first-child svg {
            width: 2.25rem;
            height: 2.25rem;
        }
        summary span:last-child {
            font-weight: 700;
            margin-left: auto;
        }
        summary:focus {
            outline: none;
        }

        details {
            border-bottom: 1px solid #b5bfd9;
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
    <h1 class="mb-4" style="text-align: center">Mobex Bonus</h1>

    <div class="row mb-2">
        <div class="col-xl-5 col-lg-6">
            <div class="card widget l-bg-cyan">
                <div class="card-statistic-3 p-4">
                    <form action="{{route('bonuses.generate-referral-link')}}" method="POST">
                        @csrf
                        <label for="basic-url">@lang('translates.referrals.link')</label>
                        @if($referral->isReal())
                            <div class="input-group">
                                <input class="form-control" id="referral-key" value="https://mobex.az/register?ref={{$referral->getAttribute('key')}}" readonly>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" id="referral-copy">
                                        <i class="fal fa-copy"></i>
                                        <span>@lang('translates.buttons.copy')</span>
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-light">@lang('translates.referrals.sub_message')</small>
                        @else
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
                                @error('key')
{{--                                    <p class="mb-1">{{$message}}</p>--}}
                                @enderror
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="card widget l-bg-blue-dark">
                <div class="card-statistic-3 p-4">
                    <div class="card-icon card-icon-large"><i class="fas fa-users"></i></div>
                    <div class="mb-3">
                        <h5 class="card-title mb-0">@lang('translates.referrals.total')</h5>
                    </div>
                    @php($efficiency = $referral->getAttribute('efficiency') ?? 0)
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-7">
                            <h2 class="d-flex align-items-center mb-0">
                                {{$referral->getAttribute('total_users') ?? 0}}
                            </h2>
                        </div>
                        <div class="col-5 text-right">
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
                <div class="card-statistic-3 px-4 py-3" style="padding-bottom: 7px">
                    <div class="card-icon card-icon-large"><i class="far fa-vial"></i></div>
                    <div class="mb-0">
                        <h5 class="card-title mb-1">@lang('translates.referrals.packages')</h5>
                    </div>
                    <div class="row align-items-center mb-1 d-flex">
                        <div class="col-12">
                            <h3 class="d-flex align-items-center mb-0">
                                {{$referral->getAttribute('total_packages') ?? 0}}
                            </h3>
                        </div>
                    </div>
                    <div class="mb-0">
                        <h5 class="card-title mb-1">@lang('translates.referrals.earnings')</h5>
                    </div>
                    <div class="row align-items-center d-flex">
                        <div class="col-12">
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
                        <h5 class="card-title mb-0">@lang('translates.referrals.bonus')</h5>
                    </div>
                    <div class="row align-items-center mb-2 d-flex">
                        <div class="col-12">
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
                <p class="text-muted m-3">@lang('translates.referrals.get_data')
                    @if($referral->getAttribute('updated_at')->diff(now())->i >= 3)
                        <a href="#" class="btn btn-link py-0" onclick="event.preventDefault(); document.getElementById('get-referral-data').submit();">
                            @lang('translates.referrals.send_req') <i class="ml-1 fal fa-smile"></i>
                        </a>
                        @if (!$referral->isNew()) (@lang('translates.referrals.updated'): {{optional($referral->getAttribute('updated_at'))->diffForHumans()}})@endif
                    @else
                         @lang('translates.referrals.retry_later', ['count' => $referral->getAttribute('updated_at')->addMinutes(3)->diffForHumans(['options' => 0, 'short' => true])])
                    @endif
                </p>
                <form id="get-referral-data" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        @endif
    </div>
    <section class="col-12">
        <h1 style="text-align: center">@lang('translates.navbar.customer_engagement') Bonus</h1>
        <h2 style="text-align:center">{{now()->subMonth()->englishMonth}}</h2>
        <form action="{{ route('calculate-amounts') }}" method="POST">
            @csrf
            @foreach($clientBonuses as $bonus)
                <input type="hidden" name="customer_engagements[]" value="{{ $bonus->id }}">
            @endforeach
            <button type="submit" class="btn btn-outline-secondary">Bonusları Hesabla</button>
        </form>
        @foreach($clientBonuses as $bonus)
            <details>
                <summary>
                    <div>
                        <h3>
                            <strong>{{$bonus->getRelationValue('client')->getAttribute('fullname')}}</strong>
                        </h3>
                        <span class="bonus">
                            @if(in_array($bonus->getRelationValue('user')->getAttribute('id'), [20, 86, 22, 154, 41]) && $bonus->getRelationValue('client')->created_at > '2023-06-01 00:00:00')
                                {{$bonus->getAttribute('amount')*0.15}}
                            @elseif(in_array($bonus->getRelationValue('user')->getAttribute('id'), [51]))
                                {{$bonus->getAttribute('amount')*0.15}}
                            @elseif(in_array($bonus->getRelationValue('user')->getAttribute('id'), [141]))
                                {{$bonus->getAttribute('amount')*0.20}}
                            @elseif(in_array($bonus->getRelationValue('user')->getAttribute('id'), [156]))
                                {{$bonus->getAttribute('amount')*0.30}}
                            @else
                                {{$bonus->getAttribute('amount')*0.10}}
                            @endif
                        </span> &nbsp; AZN
                    </div>
                </summary>
            </details>
        @endforeach
            <h4 class="float-right m-2"><b> @lang('translates.columns.total'): <span id="totalBonus"></span></b></h4>
    </section>

@endsection
@section('scripts')
    <script>
        $('#referral-copy').click(function(){
            const link = $('#referral-key');
            navigator.clipboard.writeText(link.val());
            link.addClass('is-valid');
            link.parent().addClass('is-valid');
            $(this).children('span').text('@lang('translates.buttons.copied')');
        });
    </script>
    <script>
        $(document).ready(function() {
            var total = 0;

            $('.bonus').each(function() {
                var deger = parseFloat($(this).text());
                if (!isNaN(deger)) {
                    total += deger;
                }
            });

            $('#totalBonus').text(total + ' AZN');
        });
    </script>
@endsection
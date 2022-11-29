@extends('layouts.main')

@section('title', __('translates.navbar.instruction'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.instruction')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <style>
        h2 {
            background: #07625c;
            border: 1px solid;
            text-align: center;
            padding: 5px;
            color: whitesmoke;
            cursor: pointer;
        }
    </style>
    <div class="row mb-2">
        <div class="col-12 justify-content-center">
                <h2 data-toggle="collapse" href="#task">@lang('translates.navbar.task')</h2>
                <div class="col-12 collapse" id="task">
{{--                    <video width="320" height="240" controls>--}}
{{--                        <source src="movie.mp4" type="video/mp4">--}}
{{--                    </video>--}}
                </div>

                <h2 data-toggle="collapse" href="#inquiry">@lang('translates.navbar.inquiry')</h2>
                <div class="collapse" id="inquiry">
{{--                    <iframe class="mr-5" width="100%" style="height: 400px" src="https://www.youtube.com/embed/dNMOj-3Fwls" title="Inquiry"  allowfullscreen></iframe>--}}
                </div>

                <h2 data-toggle="collapse" href="#calendar">@lang('translates.navbar.calendar')</h2>
                <div class="collapse" id="calendar">
                    <video class="mr-5" width="100%" controls><source src="{{asset('videos/teqvim.mp4')}}" type="video/mp4"></video>
                </div>

                <h2 data-toggle="collapse" href="#work">@lang('translates.navbar.work')</h2>
                <div class="collapse" id="work">
                    <video class="mr-5" width="100%" controls><source src="{{asset('videos/isler.mp4')}}" type="video/mp4"></video>
                </div>
            </div>
    </div>

@endsection
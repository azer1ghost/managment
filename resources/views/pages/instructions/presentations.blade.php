@extends('layouts.main')

@section('title', trans('translates.navbar.presentation'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.presentation')
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

            <h2 data-toggle="collapse" href="#broker">Mobil Broker</h2>
            <div class="collapse" id="broker">
                <a href="{{asset('assets/images/presentations/M.Broker - AZE - edit (2).pdf')}}" class="text-primary m-3"><i class="far fa-file-pdf fa-3x">Mobil Broker Az</i></a>
                <a href="{{asset('assets/images/presentations/Mobil Broker - ENG (2).pdf')}}" class="m-3"><i class="far fa-file-pdf fa-3x text-primary">Mobil Broker En</i></a>
                <a href="{{asset('assets/images/presentations/Mobil Broker - RUS (2) - edit.pdf')}}" class="text-primary m-3"><i class="far fa-file-pdf fa-3x">Mobil Broker Rus</i></a>
            </div>

            <h2 data-toggle="collapse" href="#logistics">Mobil Logistics</h2>
            <div class="collapse" id="logistics">
                <a href="{{asset('assets/images/presentations/Mobil Logistics - AZE (2).pdf')}}" class="text-primary m-3"><i class="far fa-file-pdf fa-3x">Mobil Logistics Az</i></a>
                <a href="{{asset('assets/images/presentations/Mobil Logistics - ENG (2) - edit.pdf')}}" class="text-primary m-3"><i class="far fa-file-pdf fa-3x">Mobil Logistics En</i></a>
                <a href="{{asset('assets/images/presentations/Mobil Logistics - RUS (2) - edit.pdf')}}" class="text-primary m-3"><i class="far fa-file-pdf fa-3x">Mobil Logistics Ru</i></a>
            </div>

            <h2 data-toggle="collapse" href="#group">Mobil Group</h2>
            <div class="collapse justify-content-center" id="group">
                <a href="{{asset('assets/images/presentations/Mobil Group - AZE (2) - edit.pdf')}}" class="text-primary m-3"><i class="far fa-file-pdf fa-3x">Mobil Group Az</i></a>
                <a href="{{asset('assets/images/presentations/Mobil Group - ENG (2) - edit.pdf')}}" class="text-primary m-3"><i class="far fa-file-pdf fa-3x">Mobil Group En</i></a>
                <a href="{{asset('assets/images/presentations/Mobil Group - RUS (2) - edit.pdf')}}" class="text-primary m-3"><i class="far fa-file-pdf fa-3x">Mobil Group Ru</i></a>
            </div>
        </div>
    </div>

@endsection
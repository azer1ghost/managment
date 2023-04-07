@extends('layouts.main')

@section('title', __('translates.navbar.room'))
@section('style')
    <style>
        /*@import url("https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");*/
        /*@import url('https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700');*/
        /*@import url('https://fonts.googleapis.com/css?family=Libre+Baskerville:400,700');*/
        body{
            font-family: 'Open Sans', sans-serif;
        }
        *:hover{
            -webkit-transition: all 1s ease;
            transition: all 1s ease;
        }
        section{
            float:left;
            width:100%;
            background: #fff;
            padding:30px 0;
        }
        h1{float:left; width:100%; color:#232323; margin-bottom:30px; font-size: 14px;}
        h1 span{font-family: 'Libre Baskerville', serif; display:block; font-size:45px; text-transform:none; margin-bottom:20px; margin-top:30px; font-weight:700}
        h1 a{color:#131313; font-weight:bold;}

        .profile-card-1 {
            font-family: 'Open Sans', Arial, sans-serif;
            position: relative;
            float: left;
            overflow: hidden;
            width: 100%;
            color: #ffffff;
            text-align: center;
            height:300px;
            border:none;
        }
        .profile-card-1 .background {
            width:100%;
            vertical-align: top;
            opacity: 0.9;
            /*-webkit-filter: blur(5px);*/
            /*filter: blur(5px);*/
            transform: scale(2.8);
        }
        .profile-card-1 .card-content {
            width: 100%;
            padding: 15px 15px;
            position: absolute;
            left: 0;
            top: 45%;
        }

        .profile-card-1 h2 {
            margin: 0 0 5px;
            font-weight: 600;
            font-size:25px;
        }
        .profile-card-1 a {
            color: white;
        }
        .profile-card-1 h2 small {
            display: block;
            font-size: 15px;
            margin-top:10px;
        }
        .profile-card-1 i {
            display: inline-block;
            font-size: 16px;
            color: #ffffff;
            text-align: center;
            border: 1px solid #fff;
            width: 30px;
            height: 30px;
            line-height: 30px;
            border-radius: 50%;
            margin:0 5px;
        }
        .profile-card-1 {
            float:left;
            width:100%;
            margin-top:15px;
        }
        .profile-card-1 .icon-block a{
            text-decoration:none;
        }
        .profile-card-1 i:hover {
            background-color:#fff;
            color:#2E3434;
            text-decoration:none;
        }

    </style>
@endsection
@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.room')
        </x-bread-crumb-link>
    </x-bread-crumb>


    <section>
        <div class="container">
            <div class="row">

                @if(auth()->user()->department->id == 24)
                    <div class="col-md-3">
                        <div class="card profile-card-1">
                            <img src="https://source.unsplash.com/600x900/?nature-24" alt="profile-sample1" class="background"/>
                            <div class="card-content">
                                <a href="{{ route('rooms.create') }}" onclick="event.preventDefault();
                                        document.getElementById('chat-form-ms').submit();">
                                    <h2>Müştərilərlə əlaqələr şöbəsi</h2>
                                </a>
                                <form id="chat-form-ms"  action="{{ route('rooms.create') }}">
                                    <input type="hidden" name="department_id" value="2">
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                @foreach(\App\Models\Department::get() as $department)
                @if(auth()->user()->department->id == $department->id || auth()->user()->isDirector() || auth()->user()->isDeveloper())
                    <div class="col-md-3">
                        <div class="card profile-card-1">
                            <img src="https://source.unsplash.com/600x900/?nature-{{$department->getAttribute('id')}}" alt="profile-sample1" class="background"/>
                            <div class="card-content">
                                <a href="{{ route('rooms.create') }}" onclick="event.preventDefault();
                                        document.getElementById('chat-form-{{$department->getAttribute('id')}}').submit();">
                                    <h2>{{$department->getAttribute('name')}}</h2>
                                </a>
                                <form id="chat-form-{{$department->getAttribute('id')}}"  action="{{ route('rooms.create') }}">
                                    <input type="hidden" name="department_id" value="{{$department->getAttribute('id')}}">
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                @endforeach
            </div>
        </div>
    </section>

@endsection


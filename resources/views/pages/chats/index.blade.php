@extends('layouts.main')

@section('title', 'Chats')

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
           Chat
        </x-bread-crumb-link>
    </x-bread-crumb>
    <div class="container">
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="chat-card chat-app">
                    <div id="plist" class="people-list" >
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                            </div>
                            <input type="text" id="search-project" class="form-control" placeholder="Search...">
                        </div>
                        <ul class="list-unstyled chat-list mt-2 mb-0">
                            @foreach($users as $user)
                                <li class="clearfix user" id="{{$user->getAttribute('id')}}">
                                    <img src="{{image($user->getAttribute('avatar'))}}" alt="avatar">
                                    <div class="about">
                                        <div class="name">{{$user->getAttribute('fullname')}}</div>
                                        <div class="status"> <i class="fa fa-circle offline"></i>
                                            @if($user->unread)
                                                <span class="pending float-right pl-1" style="color:red">Oxunmamış mesaj sayı: {{$user->unread}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                            {{--                        <li class="clearfix active">--}}
                            {{--                            <img src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="avatar">--}}
                            {{--                            <div class="about">--}}
                            {{--                                <div class="name">Aiden Chavez</div>--}}
                            {{--                                <div class="status"> <i class="fa fa-circle online"></i> online </div>--}}
                            {{--                            </div>--}}
                            {{--                        </li>--}}
                            {{--                        <li class="clearfix">--}}
                            {{--                            <img src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="avatar">--}}
                            {{--                            <div class="about">--}}
                            {{--                                <div class="name">Mike Thomas</div>--}}
                            {{--                                <div class="status"> <i class="fa fa-circle online"></i> online </div>--}}
                            {{--                            </div>--}}
                            {{--                        </li>--}}
                            {{--                        <li class="clearfix">--}}
                            {{--                            <img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="avatar">--}}
                            {{--                            <div class="about">--}}
                            {{--                                <div class="name">Christian Kelly</div>--}}
                            {{--                                <div class="status"> <i class="fa fa-circle offline"></i> left 10 hours ago </div>--}}
                            {{--                            </div>--}}
                            {{--                        </li>--}}
                            {{--                        <li class="clearfix">--}}
                            {{--                            <img src="https://bootdey.com/img/Content/avatar/avatar8.png" alt="avatar">--}}
                            {{--                            <div class="about">--}}
                            {{--                                <div class="name">Monica Ward</div>--}}
                            {{--                                <div class="status"> <i class="fa fa-circle online"></i> online </div>--}}
                            {{--                            </div>--}}
                            {{--                        </li>--}}
                            {{--                        <li class="clearfix">--}}
                            {{--                            <img src="https://bootdey.com/img/Content/avatar/avatar3.png" alt="avatar">--}}
                            {{--                            <div class="about">--}}
                            {{--                                <div class="name">Dean Henry</div>--}}
                            {{--                                <div class="status"> <i class="fa fa-circle offline"></i> offline since Oct 28 </div>--}}
                            {{--                            </div>--}}
                            {{--                        </li>--}}
                        </ul>
                    </div>
                    <div class="chat" id="messages">
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection
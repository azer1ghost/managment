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
                    <div id="plist" class="people-list">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-search"></i></span>
                            </div>
                            <input type="text" id="search-project" class="form-control"
                                   placeholder="@lang('translates.buttons.search')...">
                        </div>
                        <ul class="list-unstyled chat-list mt-2 mb-0">
                            <li class="removing-list" >Mesaj Göndərilənlər</li>
                            @foreach($recentUsers as $user)
                                <li class="clearfix user removing-list" id="{{$user->getAttribute('id')}}">
                                    <img src="{{image($user->getAttribute('avatar'))}}" loading="lazy" alt="avatar" style="height: 50px;width: 50px">
                                    <div class="about">
                                        <div class="name">{{$user->getAttribute('fullname')}}</div>
                                        <div>
                                            {{--<i class="fa fa-circle offline"></i>--}}
                                            <span class="unread{{$user->getAttribute('id')}} total-unread float-right pl-1"
                                                  style="display:none; color:red">Oxunmamış mesaj sayı: </span>
                                            @if($user->unread)
                                                <span class="pending float-right pl-1" style="color:red">Oxunmamış mesaj sayı: {{$user->unread}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                            <li>Bütün İstifadəçilər</li>
                            @foreach($users as $user)
                                <li class="clearfix user searching-list" id="{{$user->getAttribute('id')}}">
                                    <img src="{{image($user->getAttribute('avatar'))}}" alt="avatar">
                                    <div class="about">
                                        <div class="name">{{$user->getAttribute('fullname')}}</div>
                                        <div>
                                            <span class="unread{{$user->getAttribute('id')}} total-unread float-right pl-1"
                                                  style="display:none; color:red">Oxunmamış mesaj sayı: </span>
                                            @if($user->unread)
                                                <span class="pending float-right pl-1" style="color:red">Oxunmamış mesaj sayı: {{$user->unread}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="chat" id="messages"> </div>
                </div>
            </div>
        </div>
    </div>

@endsection
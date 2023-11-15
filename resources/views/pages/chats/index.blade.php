@extends('layouts.main')

@section('title', 'Chats')

@section('style')
    <style>
        .chat#messages {
            position: relative;
        }

        .chat#messages > .chat-header {
            position: sticky;
            overflow: hidden;
            top: 0;
            width: 100%;
            z-index: 1;
            background-color: white;
        }

        .chat#messages > .chat-message {
            position:absolute;
            bottom: 0;
            width: 100%;
        }

        .chat#messages > .chat-history {
            overflow-y: auto;
            height: 80%;
        }
    </style>
@endsection

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            Chat
        </x-bread-crumb-link>
    </x-bread-crumb>

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
                            @foreach($users as $user)
                                <li class="searching-list clearfix user" id="{{$user->id}}">
                                    <img src="{{image($user->avatar)}}" loading="lazy" alt="avatar" class="profile">
                                    <div class="about">
                                        <div class="name">{{$user->name .' '. $user->surname}}</div>
                                        <div>
                                            {{--<i class="fa fa-circle offline"></i>--}}
                                            <span class="unread{{$user->id}} total-unread float-right pl-1"
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

@endsection
@extends('layouts.main')

@section('title', trans('translates.navbar.room'))
@section('style')

@endsection
@section('content')
    @php
     $department = \App\Models\Department::where('id',request()->get('department_id'))->first()
    @endphp
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('rooms.index')">
            @lang('translates.navbar.room')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
      {{$department->getAttribute('name')}}
        </x-bread-crumb-link>
    </x-bread-crumb>
        <section>
            <div class="row d-flex justify-content-center">
                <div class="col-md-12 col-lg-12 col-xl-10">

                    <div class="card" id="chat1" style="border-radius: 15px;">
                        <div class="card-header d-flex justify-content-between align-items-center p-3 bg-info text-white border-bottom-0"
                                style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                            <i class="fas fa-angle-left"></i>
                            <p class="mb-0 fw-bold">{{$department->getAttribute('name')}}</p>
                            <i class="fas fa-angle-right"></i>
                        </div>
                        <div class="card-body">
                        <div style="height: 700px; overflow-y: scroll" >

                            @foreach($rooms as $room)
                                <div class="d-flex flex-row @if($room->getAttribute('user_id') == auth()->id()) justify-content-end @else justify-content-start @endif  mb-5">
                                    <img class="mr-1 mt-1" src="{{image($room->getRelationValue('user')->getAttribute('avatar'))}}"
                                         alt="avatar 1" style="width: 45px; height: 100%;">
                                    <div class="p-3 ms-3" style="border-radius: 15px; background-color: rgba(57, 192, 237,.2);">
                                        <p class="display-5 mb-0">{{$room->getAttribute('message')}}</p>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                        <div class="form-outline">
                            <form
                                    {{--                                        id="form" action="{{ route('room-chat') }}"--}}
                                    action="{{ route('rooms.store') }}" method="POST"
                            >
                                @csrf
                                <input type="hidden" id="user_id" name="user_id" value="{{auth()->id()}}">
                                <input type="hidden" id="department_id" name="department_id" value="{{request()->get('department_id')}}">
                                <div class="row col-12">
                                    <div class="col-11">
                                        <input aria-label="message" type="text" name="message" class="form-control" id="chat-input" placeholder="Type your message">
                                    </div>
                                    <div class="col-1">
                                        <button class="btn btn-success" id="send-button" type="submit" ><i class="fas fa-paper-plane"></i></button>
                                    </div>
                                </div>

                            </form>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
@endsection
@section('scripts')
{{--    <script src="{{asset('assets/js/pusher/pusher.js')}}" ></script>--}}

{{--    <script>--}}

{{--        var pusher = new Pusher('5e68408656b975a4e1e4', {--}}
{{--            cluster: 'mt1'--}}
{{--        });--}}

{{--        var channel = pusher.subscribe('room-chat');--}}
{{--        channel.bind('my-event', function (data) {--}}
{{--            // data.user_id--}}
{{--            var text = '<div>' + data.message + '</div>'--}}
{{--        })--}}
{{--        $.ajax({--}}
{{--            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),--}}
{{--            method: 'POST',--}}
{{--            url: route,--}}
{{--            data: datastr,--}}
{{--            cache: false,--}}
{{--            success: function ($data) {--}}
{{--                $('#chat-input').val('');--}}
{{--            },--}}
{{--            error: function (jqXHR, status, err) {--}}
{{--            },--}}
{{--            complete: function () {--}}
{{--            }--}}
{{--        })--}}
{{--        $('#send-button').on('click', function () {--}}
{{--            var route = $('#form').attr('action');--}}

{{--            var user_id = $('#user_id').val();--}}
{{--            var message = $('#chat-input').val();--}}
{{--            var department_id = $('#department_id').val();--}}
{{--            var datastr = "department_id=" + department_id + "&user_id=" + user_id + "&message=" + message;--}}

{{--            $.ajax({--}}
{{--                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),--}}
{{--                method: 'POST',--}}
{{--                url: route,--}}
{{--                data: datastr,--}}
{{--                cache: false,--}}
{{--                success: function ($data) {--}}
{{--                    $('#chat-input').val('');--}}
{{--                },--}}
{{--                error: function (jqXHR, status, err) {--}}
{{--                },--}}
{{--                complete: function () {--}}
{{--                }--}}
{{--            })--}}
{{--        })--}}

{{--    </script>--}}
    <script>
        window.onload = function() {
            var input = document.getElementById("chat-input").focus();
        }
    </script>
@endsection

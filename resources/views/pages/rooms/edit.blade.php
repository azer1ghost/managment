@extends('layouts.main')

@section('title', trans('translates.navbar.commands'))
@section('style')
    <style>
        /*#chat1 .form-outline .form-control~.form-notch div {*/
        /*    pointer-events: none;*/
        /*    border: 1px solid;*/
        /*    border-color: #eee;*/
        /*    box-sizing: border-box;*/
        /*    background: transparent;*/
        /*}*/

        /*#chat1 .form-outline .form-control~.form-notch .form-notch-leading {*/
        /*    left: 0;*/
        /*    top: 0;*/
        /*    height: 100%;*/
        /*    border-right: none;*/
        /*    border-radius: .65rem 0 0 .65rem;*/
        /*}*/

        /*#chat1 .form-outline .form-control~.form-notch .form-notch-middle {*/
        /*    flex: 0 0 auto;*/
        /*    max-width: calc(100% - 1rem);*/
        /*    height: 100%;*/
        /*    border-right: none;*/
        /*    border-left: none;*/
        /*}*/

/

        /*#chat1 .form-outline .form-control:focus~.form-notch .form-notch-leading {*/
        /*    border-top: 0.125rem solid #39c0ed;*/
        /*    border-bottom: 0.125rem solid #39c0ed;*/
        /*    border-left: 0.125rem solid #39c0ed;*/
        /*}*/

        /*#chat1 .form-outline .form-control:focus~.form-notch .form-notch-leading,*/
        /*#chat1 .form-outline .form-control.active~.form-notch .form-notch-leading {*/
        /*    border-right: none;*/
        /*    transition: all 0.2s linear;*/
        /*}*/

        /*#chat1 .form-outline .form-control:focus~.form-notch .form-notch-middle {*/
        /*    border-bottom: 0.125rem solid;*/
        /*    border-color: #39c0ed;*/
        /*}*/

        /*#chat1 .form-outline .form-control:focus~.form-notch .form-notch-middle,*/
        /*#chat1 .form-outline .form-control.active~.form-notch .form-notch-middle {*/
        /*    border-top: none;*/
        /*    border-right: none;*/
        /*    border-left: none;*/
        /*    transition: all 0.2s linear;*/
        /*}*/

        /*#chat1 .form-outline .form-control:focus~.form-notch .form-notch-trailing {*/
        /*    border-top: 0.125rem solid #39c0ed;*/
        /*    border-bottom: 0.125rem solid #39c0ed;*/
        /*    border-right: 0.125rem solid #39c0ed;*/
        /*}*/

        /*#chat1 .form-outline .form-control:focus~.form-notch .form-notch-trailing,*/
        /*#chat1 .form-outline .form-control.active~.form-notch .form-notch-trailing {*/
        /*    border-left: none;*/
        /*    transition: all 0.2s linear;*/
        /*}*/

        /*#chat1 .form-outline .form-control:focus {*/
        /*    color: #39c0ed;*/
        /*}*/

        /*#chat1 .form-outline .form-control{*/
        /*    color: #bfbfbf;*/
        /*}*/
    </style>
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
                        <div class="card-body" style="height: 700px; overflow-y: scroll" >

                            @foreach(\App\Models\Room::where('department_id', request()->get('department_id'))->latest()->limit(500)->get()->reverse() as $room)
                                <div class="d-flex flex-row @if($room->getAttribute('user_id') == auth()->id()) justify-content-end @else justify-content-start @endif  mb-5">
                                    <img class="mr-1 mt-1" src="{{image($room->getRelationValue('user')->getAttribute('avatar'))}}"
                                         alt="avatar 1" style="width: 45px; height: 100%;">
                                    <div class="p-3 ms-3" style="border-radius: 15px; background-color: rgba(57, 192, 237,.2);">
                                        <p class="display-5 mb-0">{{$room->getAttribute('message')}}</p>
                                    </div>
                                </div>
                            @endforeach

                            <div class="form-outline message-wrapper">
                                <form action="{{ route('rooms.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{auth()->id()}}">
                                    <input type="hidden" name="department_id" value="{{request()->get('department_id')}}">
                                    <div class="row col-12"
{{--                                         style="position: absolute; bottom: 15px"--}}
                                    >
                                        <div class="col-11">
                                        <input aria-label="message" name="message" class="form-control" id="chat-input" placeholder="Type your message">
                                        </div>

                                        <div class="col-1">
                                        <button class="btn btn-success" type="submit"><i class="fas fa-paper-plane"></i></button>
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
    <script>
        window.onload = function() {
            var input = document.getElementById("chat-input").focus();
        }
    </script>
@endsection

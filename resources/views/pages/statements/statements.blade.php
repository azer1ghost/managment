@extends('layouts.main')

@section('title', trans('translates.navbar.announcement'))
@section('style')
    <style>
        p,h4,a {
            color: black;
        }
        a {
            font-weight: bold;
        }
        .alerts .alert {
            position: relative;
            border: none !important;
            margin-bottom: 8px;
            text-shadow: 0 -1px 1px rgba(0,0,0,0.2);
        }
        .alerts .alert .icon {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            margin-left: -15px;
            width: 80px;
            text-align: center;
        }
        .alerts .alert .copy {
            margin-left: 60px;
            padding-left: 20px;
            border-left: 1px solid rgba(255,255,255,0.3);
            line-height: 1.2;
        }
        .alerts .alert .copy h4 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .alerts .alert .copy p {
            font-size: 14px;
            margin-bottom: 0;
        }
        .alerts .alert .mark-as-read {
            opacity: 1;
            width: 200px;
            position: absolute;
            top: 50%;
            right: -15px;
            transform: translateY(-50%);
        }
        .alerts .alert.alert-info {
            background-color: #A3ADB2;
            color: #eee;
            /*box-shadow: 0 0 20px 5px rgba(163,173,178,0.5);*/
        }
    </style>
@endsection
@section('content')

<div class="container-fluid">

    <div class="alerts">
        @php
            $unReadNotifications = auth()->user()->notifications->where('type', 'App\Notifications\NotifyStatement')->all();
        @endphp
        @foreach($unReadNotifications as $notification)

        <div @if($notification->unread()) style="background-color: #ace5ce" @endif class="row alert alert-info animated bounceInRight">
            <div class="col-1 icon pull-left">
                <i class="{{$notification->data['attribute']}} fa-2x"></i>
            </div>
            <div  class="copy col-8">
                <h4>{{$notification->data['title']}}</h4>
                <p>{!!$notification->data['body']!!}</p>
            </div>
            @if($notification->unread())
            <a href="#" class="mark-as-read col-3 mark-as-read" data-id="{{$notification->id}}">
                @lang('translates.general.mark_as_read')
            </a>
            @endif
        </div>
            @if($loop->last)
                <a href="#" id="mark-all" class="btn btn-secondary text-black mt-2">
                    @lang('translates.general.mark_all')
                </a>
            @endif
        @endforeach
    </div>
</div>

@endsection
@section('scripts')
    <script>
        function sendMarkRequest(id = null) {
            return $.ajax("{{ route('mark-as-read') }}", {
                method: 'POST',
                data: {
                    id
                }
            });
        }
        $(function () {
            $('.mark-as-read').click(function () {
                let request = sendMarkRequest($(this).data('id'));
                request.done(() => {
                    $(this).parent('div').removeAttr('style');
                    $(this).hide();
                });
            });
            $('#mark-all').click(function () {
                let request = sendMarkRequest();
                request.done(() => {
                    $('.bounceInRight').removeAttr("style ");
                    $('.mark-as-read').hide();
                });
            })
        })
    </script>
@endsection
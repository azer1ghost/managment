<div class="chat-header clearfix">
    <div class="row">
        <div class="col-lg-12">
            <a href="javascript:void(0);" data-toggle="modal" data-target="#view_info">
                <img src="{{image($user->getAttribute('avatar'))}}" alt="avatar" class="profile">
            </a>
            <div class="chat-about">
                <h6 class="m-b-0">{{$user->getAttribute('fullname')}}</h6>
                <small>{{$user->getAttribute('fullname_with_position')}}</small>
            </div>
        </div>
{{--        <div class="col-lg-6 hidden-sm text-right">--}}
{{--            <a href="javascript:void(0);" class="btn btn-outline-secondary"><i class="fa fa-camera"></i></a>--}}
{{--            <a href="javascript:void(0);" class="btn btn-outline-primary"><i class="fa fa-image"></i></a>--}}
{{--            <a href="javascript:void(0);" class="btn btn-outline-info"><i class="fa fa-cogs"></i></a>--}}
{{--            <a href="javascript:void(0);" class="btn btn-outline-warning"><i class="fa fa-question"></i></a>--}}
{{--        </div>--}}
    </div>
</div>
<div class="chat-history message-wrapper">
    <ul class="m-b-0">
        @foreach($messages as $message)
            <li class="clearfix">
                <div class="message-data @if($message->getAttribute('from') == auth()->id()) text-right @endif">
                    <span class="message-data-time">{{$message->getAttribute('created_at')->diffForHumans()}}</span>
                    @if($message->getAttribute('from') == auth()->id())
                        <img src="{{image(auth()->user()->getAttribute('avatar'))}}" alt="avatar">
                    @else
                        <img src="{{image($user->getAttribute('avatar'))}}" alt="avatar">
                    @endif

                </div>
                <div class="message other-message @if($message->getAttribute('from') == auth()->id()) float-right @endif">
                    {{$message->getAttribute('message')}}
                </div>
            </li>
        @endforeach

    </ul>
</div>
<div class="chat-message clearfix">
    <div class="input-group mb-0 input-text chat-text-entered">
        <input type="text" class="chat-input input form-control" placeholder="">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-share"></i></span>
        </div>
    </div>
</div>
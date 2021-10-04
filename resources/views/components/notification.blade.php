<a href="{{$notification['url']}}">
    <div class="media">
        <img src="{{image($notification['user']['avatar'])}}" class="mr-3 profile" alt="logo" style="width: 30px;height: 30px">
        <div class="media-body">
            <h6 class="my-0" style="font-size: 12px;color: #000">
                @lang($notification['message'])
                @if(now()->diff($notification['read_at'])->i < 1)
                    <i class="fa fa-circle text-success ml-2 small" data-toggle="tooltip" data-placement="top" title="New"></i>
                @endif
            </h6>
            <p  class="mb-3" style="font-size: 10px;color: #000 !important;"><a href="#" style="font-size: inherit !important;color: #0d2366 !important;">{{$notification['user']['fullname']}}:</a> {{$notification['content']}}</p>
        </div>
    </div>
</a>

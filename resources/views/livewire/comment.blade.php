<li class="media shadow p-3 mb-2 bg-white rounded"  wire:key="{{$comment->getAttribute('id')}}">
    @if(!$isSub)
    <img src="{{image($comment->user->getAttribute('avatar'))}}" class="mr-2 rounded-circle" style="width: 40px">
    @endif
    <div class="media-body">
        <h5 class="mt-0 mb-1">
            {{$comment->user->getAttribute('fullname')}}
            <small class="float-right">
                <i class="fal fa-eye"></i>
                {{$comment->viewers_count}}
            </small>
        </h5>
        <p class="mb-0">{{ $comment->getAttribute('content') }}</p>
        @if($comment->comments()->exists())
            <livewire:comments :data="$comment" is-sub="1"/>
        @endif
    </div>
</li>



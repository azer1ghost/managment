<li class="media shadow p-3 mb-2 bg-white rounded"  wire:key="{{$comment->id}}">
    <img src="{{image($comment->user->avatar)}}" class="mr-2 rounded-circle" style="width: 40px">
    <div class="media-body">
        <h5 class="mt-0 mb-1">
            {{$comment->user->fullname}}
            <small class="float-right">
                <i class="fal fa-ellipsis-v-alt"></i>
            </small>
        </h5>
        <p class="mb-0">{{ $comment->content }}</p>
        <div class="col-12 p-0 mt-3">
            <small class="mr-2">
                <i class="fal fa-eye"></i>
                {{$comment->viewers_count}}
            </small>
            <small class="mr-2">
                <i class="fal fa-comment"></i>
                {{$comment->comments_count}}
            </small>
            <button wire:click.prevent="reply({{$comment->id}})" class="btn btn-link text-info m-0 p-0" >
                <i class="fal fa-reply"></i> reply
            </button>

            <button wire:click.prevent="delete({{$comment->id}})" class="btn btn-link text-danger m-0 p-0" >
                <i class="fal fa-trash"></i> delete
            </button>

            <button wire:click.prevent="edit({{$comment->id}})" class="btn btn-link text-success m-0 p-0" >
                <i class="fal fa-pencil"></i> edit
            </button>

        </div>
        @if(isset($comment->comments))
            <x-comments :comments="$comment->comments->toArray()" />
        @endif
    </div>
</li>
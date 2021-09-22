<li class="media shadow p-3 mb-2 bg-white rounded"  wire:key="{{$comment->id}}">
    <img src="{{image($comment->user->avatar)}}" class="mr-2 rounded-circle" style="width: 40px">
    <div class="media-body">
        <h5 class="mt-0 mb-1">
            {{$comment->user->fullname}}
            <small class="float-right">
                <div class="btn-sm-group d-flex align-items-center justify-content-center">
                    <div class="dropdown">
                        <button class="btn" type="button" id="comment_actions-{{$comment->id}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fal fa-ellipsis-v-alt"></i>
                        </button>
                        <div class="dropdown-menu custom-dropdown" style="min-width: 0 !important;">
                            @can('update', $comment)
                                <button wire:click.prevent="edit({{$comment->id}})" class="dropdown-item btn text-success">
                                    <i class="fal fa-pencil"></i> edit
                                </button>
                            @endcan
                            @can('delete', $comment)
                                <button wire:click.prevent="delete({{$comment->id}})" class="dropdown-item btn text-danger ">
                                    <i class="fal fa-trash"></i> delete
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
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
        </div>
        @if(isset($comment->comments))
            <x-comments :comments="$comment->comments->toArray()" />
        @endif
    </div>
</li>
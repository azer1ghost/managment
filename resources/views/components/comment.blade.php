<li class="media shadow p-3 mb-2 bg-white rounded"  wire:key="{{$comment->id}}">
    @if($isNotSub)
    <img src="{{image($comment->user->avatar)}}" class="mr-2 rounded-circle" style="width: 40px">
    @endif
    <div class="media-body">
        <h5 class="mt-0 mb-1">

            {{$comment->user->fullname}}

            <small class="text-muted ml-2"  data-toggle="tooltip" title="{{$comment->created_at}}">
                @if(now()->diff($comment->created_at)->d > 2)
                    {{$comment->created_at->translatedFormat('j F Y')}}
                @else
                   {{$comment->created_at->diffForHumans(['options' => 0, 'short' => true])}}
                @endif
            </small>

            @if($comment->created_at != $comment->updated_at)
                <small class="text-muted ml-2">(edited)</small>
            @endif

            @if(($comment->user->id != auth()->id()) && (now()->diff($comment->wasViewedAt())->i < 1))
                <i class="fa fa-circle text-success ml-2 small" data-toggle="tooltip" title="New"></i>
            @endif

            <small class="float-right">
                <div class="btn-sm-group d-flex align-items-center justify-content-center">
                    <div class="dropdown">
                        <button class="btn" type="button" id="comment_actions-{{$comment->id}}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fal fa-ellipsis-v-alt"></i>
                        </button>
                        <div class="dropdown-menu custom-dropdown" style="min-width: 0 !important;">
                            @can('update', $comment)
                                <button wire:click.prevent="edit({{$comment->id}})" class="dropdown-item btn text-success">
                                    <i class="fal fa-pencil"></i> @lang('translates.tasks.edit')
                                </button>
                            @endcan
                            @can('delete', $comment)
                                <button wire:click.prevent="delete({{$comment->id}})" class="dropdown-item btn text-danger ">
                                    <i class="fal fa-trash"></i> @lang('translates.tasks.delete')
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
            @if($isNotSub)
                <small class="mr-2">
                    <i class="fal fa-comment"></i>
                    {{$comment->comments_count}}
                </small>
            @endif
            <button wire:click.prevent="reply({{$comment->id}})" class="btn btn-link text-info m-0 p-0" >
                <i class="fal fa-reply"></i> @lang('translates.tasks.reply')
            </button>
        </div>
        @if(isset($comment->comments))
            <x-comments :comments="$comment->comments->toArray()" :is-sub="true" />
        @endif
    </div>
</li>
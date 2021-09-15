<div>
    @if(optional(optional($commentable)->comments())->exists())
        <livewire:comments :data="$commentable"/>
    @endif

    <ul class="list-unstyled shadow p-3 mb-1 bg-white rounded">
        <li class="media">
            <img src="{{image(auth()->user()->getAttribute('avatar'))}}" class="mr-2 rounded-circle" style="width: 40px">
            <div class="media-body">
                <h5 class="mt-0 mb-1">{{auth()->user()->getAttribute('fullname')}}</h5>
                <textarea class="form-control" style="min-height: 20px" placeholder="Enter your comment here" rows="3"></textarea>
                <button class="btn btn-primary btn-sm float-right mt-2">
                    <i class="fas fa-paper-plane mr-1"></i>
                    Send
                </button>
            </div>
        </li>
    </ul>
</div>
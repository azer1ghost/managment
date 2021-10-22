<div wire:poll.visible.5000ms>
    <ul class="list-unstyled shadow p-3 mb-1 bg-white rounded">
        <li class="media">
            <img src="{{image(auth()->user()->getAttribute('avatar'))}}" class="mr-2 rounded-circle" style="width: 40px">
            <div class="media-body" wire:ignore>
                <h5 class="mt-0 mb-1">{{auth()->user()->getAttribute('fullname')}}</h5>
                    <textarea
                            class="form-control" id="message" rows="2"
                            style="min-height: 20px"
                            wire:model="message"
                    ></textarea>

                <button wire:click="sendComment" class="btn btn-primary btn-sm float-right mt-2">
                    <i class="fas fa-paper-plane mr-1"></i>
                    @lang('translates.tasks.send')
                </button>
            </div>
        </li>
    </ul>
    @if(isset($comments['data']) && count($comments['data']))
        <x-comments :comments="$comments['data']"/>

        <div class="col-12 mt-4 d-flex justify-content-center">
            @if(isset($comments['next_page_url']) && !is_null($comments['next_page_url']))
                <button wire:click.prevent="loadMore" class="btn btn-primary btn-sm float-right mt-2">
                    Load More
                </button>
            @else
                <p>@lang('translates.tasks.end')</p>
            @endif
        </div>
    @else
        <div class="col-12 mt-4 d-flex justify-content-center">
            <p>@lang('translates.tasks.no_comments')</p>
        </div>
    @endif

</div>

@push('scripts')
    <script>
        window.livewire.on('focus-to-message', function (data){
            $("#message").val(data).focus();
        });
    </script>
@endpush
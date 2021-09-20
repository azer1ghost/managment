<div>

    <ul class="list-unstyled shadow p-3 mb-1 bg-white rounded">
        <li class="media">
            <img src="{{image(auth()->user()->getAttribute('avatar'))}}" class="mr-2 rounded-circle" style="width: 40px">
            <div class="media-body">
                <h5 class="mt-0 mb-1">{{auth()->user()->getAttribute('fullname')}}</h5>
                <div contenteditable="true" class="form-control" id="message" style="min-height: 20px" placeholder="Enter your comment here" rows="3"></div>
                <button wire:click="sendComment" class="btn btn-primary btn-sm float-right mt-2">
                    <i class="fas fa-paper-plane mr-1"></i>
                    @lang('Send')
                </button>
            </div>
        </li>
    </ul>

    <x-comments :comments="$comments['data']"/>

    <div class="col-12 mt-4 d-flex justify-content-center">
        @if(!is_null($comments['next_page_url']))
            <button wire:click.prevent="loadMore" class="btn btn-primary btn-sm float-right mt-2">
                Load More
            </button>
        @else
            <p>Thas's all</p>
        @endif
    </div>

</div>

@push('scripts')
    <script>
        document.getElementById("message").addEventListener("input", inputEvt => {
            console.log("input event fired");
        }, false);

        window.livewire.on('focus-to-message', function (fullname) {
            $("#message").html(`@${fullname} `).focus();
        });
    </script>
@endpush
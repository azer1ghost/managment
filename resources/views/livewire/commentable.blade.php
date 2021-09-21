<div>

    <ul class="list-unstyled shadow p-3 mb-1 bg-white rounded">
        <li class="media">
            <img src="{{image(auth()->user()->getAttribute('avatar'))}}" class="mr-2 rounded-circle" style="width: 40px">
            <div class="media-body" wire:ignore>
                <h5 class="mt-0 mb-1">{{auth()->user()->getAttribute('fullname')}}</h5>
                    <textarea

                            contenteditable="true"
                            class="form-control" id="message" rows="5"
                            style="min-height: 20px"
                            wire:model="message"
                    ></textarea>

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

        let data = [
            {value: 'alex', uid: 'user:1'},
            {value: 'andrew', uid: 'user:2'},
            {value: 'angry birds', uid: 'game:5'},
            {value: 'assault', uid: 'game:3'}
        ];

        $('textarea').mentionsInput({source: data, suffix: ' '});

        window.livewire.on('focus-to-message', function (fullname){
            $("#message").html(`<span id="comment-tag" class="text-info">@${fullname}</span>`).focus();

             // moveCursorToEnd(document.getElementById('comment-tag'))
        });

       function moveCursorToEnd(el){
            if(el.innerHTML && document.createRange)
            {
                window.setTimeout(() =>
                    {
                        let selection = document.getSelection();
                        let range = document.createRange();

                        range.setStart(el.childNodes[0], el.innerHTML.length);
                        range.collapse(true);
                        selection.removeAllRanges();
                        selection.addRange(range);
                    }
                    ,1);
            }
        }
    </script>
@endpush
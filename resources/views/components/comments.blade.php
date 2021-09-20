@if(count($comments))
    <ul class="list-unstyled mb-0 mt-3">
        @foreach($comments as $comment)
            <x-comment :comment="$comment"/>
        @endforeach
    </ul>
@endif

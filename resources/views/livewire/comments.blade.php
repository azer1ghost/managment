<ul class="list-unstyled mb-0 mt-3">
    @foreach($this->comments as $comment)
     <livewire:comment :comment="$comment" :is-sub="$isSub"/>
    @endforeach
</ul>

<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Comment extends Component
{
    public \App\Models\Comment $comment;
    public bool $isSub;
    public bool $isNotSub;

    public function __construct($comment, $isSub = false)
    {
        $this->isSub = $isSub;
        $this->isNotSub = !$isSub;

        $this->comment = \App\Models\Comment::withCount(['viewers', 'comments'])->find($comment['id']);

        $this->comment->viewers()->syncWithoutDetaching([auth()->id()]);
    }

    public function render()
    {
        return view('components.comment');
    }
}

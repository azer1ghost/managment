<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Comment extends Component
{
    public $comment;

    public function __construct($comment)
    {
        $this->comment = \App\Models\Comment::withCount(['viewers', 'comments'])->find($comment['id']);

        $this->comment->viewers()->syncWithoutDetaching([auth()->id()]);
    }

    public function render()
    {
        return view('components.comment');
    }
}

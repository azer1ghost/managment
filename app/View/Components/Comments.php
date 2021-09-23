<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Comments extends Component
{
    public array $comments = [];
    public bool $isSub;
    public bool $isNotSub;

    public function __construct($comments = [], $isSub = false)
    {
        $this->isSub = $isSub;
        $this->isNotSub = !$isSub;

        $this->comments = $comments;
    }

    public function render()
    {
        return view('components.comments');
    }
}

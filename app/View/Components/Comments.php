<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Comments extends Component
{
    public array $comments = [];

    public function __construct($comments = [])
    {
        $this->comments = $comments;
    }

    public function render()
    {
        return view('components.comments');
    }
}

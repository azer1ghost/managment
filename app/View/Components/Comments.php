<?php

namespace App\View\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class Comments extends Component
{
    public ?Model $commentable;

    public function __construct($commentable)
    {
        $this->commentable = $commentable;
    }

    public function render()
    {
        return view('components.comments');
    }
}

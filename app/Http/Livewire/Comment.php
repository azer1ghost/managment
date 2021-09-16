<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Comment extends Component
{
    public \App\Models\Comment $comment;

    public bool $isSub = false;

    public function mount()
    {
        $this->comment->viewers()->syncWithoutDetaching([auth()->id()]);
    }

    public function render()
    {
        return view('livewire.comment');
    }
}
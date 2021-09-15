<?php

namespace App\Http\Livewire;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class Comments extends Component
{
    public Model $data;

    public bool $isSub = false;

    public function getCommentsProperty()
    {
        return $this->data->comments()->withCount('viewers')->get();
    }

    public function render()
    {
        return view('livewire.comments');
    }
}
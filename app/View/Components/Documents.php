<?php

namespace App\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Documents extends Component
{
    public Collection $documents;

    public function __construct(Collection $documents)
    {
        $this->documents = $documents;
    }

    public function render()
    {
        return view('components.documents');
    }
}

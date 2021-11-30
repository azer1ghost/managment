<?php

namespace App\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Documents extends Component
{
    public Collection $documents;
    public ?string $title;

    public function __construct(Collection $documents, string $title = null)
    {
        $this->documents = $documents;
        $this->title = $title ?? trans('translates.files.default_title');
    }

    public function render()
    {
        return view('components.documents');
    }
}

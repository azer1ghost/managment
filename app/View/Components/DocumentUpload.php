<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DocumentUpload extends Component
{
    public string $model;
    public int $id;

    public function __construct($id, $model)
    {
        $this->id = $id;
        $this->model = $model;
    }

    public function render()
    {
        return view('components.document-upload');
    }
}

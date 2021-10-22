<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Fieldsets extends Component
{
    public bool $isOutsource;

    public function __construct($isOutsource = false)
    {
        $this->isOutsource = $isOutsource;
    }

    public function render()
    {
        return view('components.fieldsets');
    }
}

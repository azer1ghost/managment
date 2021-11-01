<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Fieldsets extends Component
{
    public bool $isOutsource;
    public array $serialPattern;

    public function __construct($serialPattern = [], $isOutsource = false)
    {
        $this->isOutsource = $isOutsource;
        $this->serialPattern = $serialPattern;
    }

    public function render()
    {
        return view('components.fieldsets');
    }
}

<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Fieldset extends Component
{
    public string $header = '';
    public int $step = 0;

    public function __construct($header, $step)
    {
        $this->header = $header;
        $this->step = $step;
    }

    public function render()
    {
        return view('components.fieldset');
    }
}

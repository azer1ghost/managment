<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Diamond extends Component
{

    public $color;

    public function __construct($color = '050E3ABC')
    {
       $this->color = $color;
    }

    public function render()
    {
        return /** @lang HTML */
            <<<'blade'
             <svg {{ $attributes }} xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50000 50000">
                <path style="fill: {{$color}} ;fill-rule:nonzero" d="M32849.2 39316.1l-16262.1 0-6723.5-8362.9 14836.2-19679.7 14860.3 19724.3-6710.9 8318.3zm-8151.1-33686.5l-19108.7 25248.7 9409.9 11476.7 19439 0 9396.2-11478.3-19136.4-25247.1z"></path>
             </svg>
        blade;
    }
}

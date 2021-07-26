<?php

namespace App\View\Components\Bread\Input;

use Illuminate\View\Component;

class Submit extends Component
{
    public function __construct(
        public string   $value = 'Submit',
        public ?int     $width = 12,
    )
    {}

    public function render()
    {
        return /** @lang Blade */
        <<<'blade'
            <div {{ $attributes->merge(['class' => 'text-right col-12 col-md-'.$width]) }}>
                <hr>
                <button type="submit" class="btn btn-outline-primary">{{$value}}</button>
            </div>
        blade;
    }
}

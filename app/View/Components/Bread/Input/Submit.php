<?php

namespace App\View\Components\Bread\Input;

use Illuminate\View\Component;

class Submit extends Component
{
    public function __construct(
        public string   $value = 'Submit',
        public ?int     $width = 12,
        public ?string  $type = 'submit',
        public ?string  $color = 'primary',
        public ?string  $layout = 'right',
    )
    {}

    public function render()
    {
        return /** @lang Blade */
        <<<'blade'
            <div {{ $attributes->merge(['class' => 'text-'.$layout.' col-12 col-md-'.$width]) }}>
                @if($type === 'submit') <hr> @endif
                <button type="{{$type}}" class="btn btn-outline-{{$color}}">{!! $value !!}</button>
            </div>
        blade;
    }
}

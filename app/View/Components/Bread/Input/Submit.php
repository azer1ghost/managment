<?php

namespace App\View\Components\Bread\Input;

use Illuminate\View\Component;

class Submit extends Component
{
    public ?int $width;
    public ?string $value;
    public ?string $color;
    public ?string  $layout;
    public ?string  $type;


    public function __construct($type = 'submit', $value = 'Submit', $width = 12, $color = 'primary', $layout = 'right')
    {
        $this->type = $type;
        $this->value = $value;
        $this->color = $color;
        $this->width = $width;
        $this->layout = $layout;
    }

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

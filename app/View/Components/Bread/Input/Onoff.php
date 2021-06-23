<?php

namespace App\View\Components\Bread\Input;

use Illuminate\View\Component;

class Onoff extends Component
{
    public function __construct(
        public ?string  $name  = "Onoff",
        public ?bool    $value = false,
        public ?string  $label = 'Status',
        public ?int     $width = 2,
    )
    {}

    public function render()
    {
        return /** @lang Blade */
        <<<'blade'
          <div {{ $attributes->merge(['class' => 'form-group col-6 col-md-'.$width]) }}>
                <p>{{$label ?? Str::ucfirst($name).' input'}}</p>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="{{$name}}" id="data-true-{{$name}}" value="1" @if($value) checked @endif>
                    <label class="form-check-label" for="data-true-{{$name}}">On</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="{{$name}}" id="data-false-{{$name}}" value="0" @if(!$value) checked @endif>
                    <label class="form-check-label" for="data-false-{{$name}}">Off</label>
                </div>
            </div>
        blade;
    }
}

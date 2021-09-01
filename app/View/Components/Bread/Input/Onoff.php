<?php

namespace App\View\Components\Bread\Input;

use Illuminate\View\Component;

class Onoff extends Component
{
    public ?int     $width;
    public ?string  $label;
    public bool     $value;
    public ?string  $name;

    public function __construct($name = 'Onoff', $value = false, $label = 'Status', $width = 2)
    {
        $this->name  = $name;
        $this->value = $value;
        $this->label = $label;
        $this->width = $width;
    }

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

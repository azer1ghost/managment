<?php

namespace App\View\Components\Bread\Input;

use Illuminate\View\Component;

class Number extends Component
{
    public ?string $width;
    public ?string $label;
    public ?string $value;
    public ?string $name;

    public function __construct($name = null, $value = null, $label = null, $width = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->label = $label;
        $this->width = $width;
    }

    public function render()
    {
        return /** @lang Blade */
        <<<'blade'
          <div {{ $attributes->merge(['class' => 'form-group col-12 col-md-'.$width]) }}>
                <label for="data-{{$name}}">{{$label ?? Str::ucfirst($name).' input'}}</label>
                <input type="number" class="form-control @error($name) is-invalid @enderror" name="{{$name}}" id="data-{{$name}}" placeholder="Type {{Str::lower($label)}}" @if($value) value="{{$value}}" @endif >
                @error($name)
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        blade;
    }
}

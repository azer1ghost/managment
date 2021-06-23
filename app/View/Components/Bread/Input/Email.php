<?php

namespace App\View\Components\Bread\Input;

use Illuminate\View\Component;

class Email extends Component
{
    public $width = null;
    public $label = null;
    public $value = null;
    public $name = null;

    public function __construct(
        ?string $name  = null,
        ?string $value = null,
        ?string $label = null,
        ?int $width = 12
    )
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
                <label for="data-{{$name}}">{{$label ?? Str::ucfirst($name)}}</label>
                <input type="email" class="form-control @error($name) is-invalid @enderror" name="{{$name}}" id="data-{{$name}}" placeholder="Type {{Str::lower($label ?? $name)}}" value="{{$value ?? old($name)}}">
                @error($name)
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        blade;
    }
}

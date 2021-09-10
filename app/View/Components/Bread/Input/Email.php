<?php

namespace App\View\Components\Bread\Input;

use Illuminate\View\Component;

class Email extends Component
{
    public ?string $width;
    public ?string $label;
    public ?string $value;
    public ?string $name;
    public ?string $placeholder;

    public function __construct($name = null, $value = null, $label = null, $width = null, $placeholder = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->label = $label;
        $this->width = $width;
        $this->placeholder = $placeholder;

    }
    
    public function render()
    {
        return /** @lang Blade */
        <<<'blade'
          <div {{ $attributes->merge(['class' => 'form-group col-12 col-md-'.$width]) }}>
                <label for="data-{{$name}}">{{$label ?? Str::ucfirst($name)}}</label>
                <input type="email" class="form-control @error($name) is-invalid @enderror" name="{{$name}}" id="data-{{$name}}" @if($value) value="{{$value}}" @endif placeholder="{{ $placeholder ?? 'Enter '. Str::lower($label ?? $name) }}" >
                @error($name)
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        blade;
    }
}

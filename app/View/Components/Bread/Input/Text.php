<?php

namespace App\View\Components\Bread\Input;

use Illuminate\View\Component;

class Text extends Component
{
    public ?int $width;
    public ?string $label;
    public ?string $value;
    public ?string $name;
    public ?string $type;
    public ?string $placeholder;

    public function __construct($name = null, $value = null, $label = null, $width = 12, $type = 'text', $placeholder = null)
    {
        $this->name = $name;
        $this->value = $value;
        $this->label = $label;
        $this->width = $width;
        $this->type = $type;
        $this->placeholder = $placeholder;
    }

    public function render()
    {
        return /** @lang Blade */
        <<<'blade'
          <div {{ $attributes->merge(['class' => 'form-group col-12 col-md-'.$width]) }}>
                <label for="data-{{$name}}">{{$label ?? Str::ucfirst($name)}}</label>
                <input 
                       @if(key_exists('required', $attributes->getAttributes())) required @endif
                       @if(key_exists('readonly', $attributes->getAttributes())) readonly @endif
                       @if(key_exists('max',      $attributes->getAttributes())) max='{{$attributes['max']}}' @endif
                       type="{{$type}}"
                       class="form-control @error($name) is-invalid @enderror"
                       name="{{$name}}" 
                       id="data-{{$name}}" 
                       placeholder="{{ $placeholder ?? 'Enter '. Str::lower($label ?? $name) }}" 
                       @if($value || old($name))  value="{{$value ?? old($name)}}" @endif >
                @error($name)
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        blade;
    }
}

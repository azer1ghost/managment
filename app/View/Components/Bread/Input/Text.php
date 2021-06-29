<?php

namespace App\View\Components\Bread\Input;

use Illuminate\View\Component;

class Text extends Component
{
    public $width = null;
    public $label = null;
    public $value = null;
    public $name = null;
    public $type = null;
    public $placeholder = null;

    public function __construct(
        ?string $name  = null,
        ?string $value = null,
        ?string $label = null,
        ?string $placeholder = null,
        ?string $type = "text",
        ?int $width = 12
    )
    {
        $this->name = $name;
        $this->value = $value;
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->width = $width;
        $this->type = $type;
    }

    public function render()
    {
        return /** @lang Blade */
        <<<'blade'
          <div {{ $attributes->merge(['class' => 'form-group col-12 col-md-'.$width]) }}>
                <label for="data-{{$name}}">{{$label ?? Str::ucfirst($name)}}</label>
                <input @if(key_exists('required', $attributes->getAttributes())) required @endif
                       type="{{$type}}" 
                       class="form-control @error($name) is-invalid @enderror"
                       name="{{$name}}" 
                       id="data-{{$name}}" 
                       placeholder="{{ $placeholder ?? 'Enter '. Str::lower($label ?? $name) }}" 
                       value="{{$value ?? old($name)}}">
                @error($name)
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        blade;
    }
}

<?php

namespace App\View\Components\Bread\Input;

use Illuminate\View\Component;

class Select extends Component
{
    public ?int     $width;
    public ?string  $label;
    public ?string  $value;
    public ?string  $name;
    public array    $options;
    public ?int     $default;

    public function __construct
    (
        $default = 0,
        $options = [],
        $name = null,
        $value = null,
        $label = null,
        $width = null
    )
    {
        $this->name = $name;
        $this->value = $value;
        $this->label = $label;
        $this->width = $width;
        $this->options = $options;
        $this->default = $default;
    }

    public function render()
    {
        return /** @lang Blade */
        <<<'blade'
          <div {{ $attributes->merge(['class' => 'form-group col-12 col-md-'.$width]) }}>
                <label for="data-{{$name}}">{{$label ?? Str::ucfirst($name)}}</label>
                 <select  @if(key_exists('wire:model', $attributes->getAttributes())) wire:model="{{$attributes->getAttributes()['wire:model']}}" @endif class="form-control @error($name) is-invalid @enderror" name="{{$name}}" id="data-{{$name}}" style="padding: .375rem 0.75rem !important;">
                     @if($default) <option disabled selected value="null">{{$label}} {{__('translates.placeholders.choose')}}</option> @endif
                     @foreach($options as $key => $option)
                        <option @if($key == $value) selected @endif value="{{$key}}">{{$option}}</option>
                     @endforeach
                </select>
                @error($name)
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
        blade;
    }
}

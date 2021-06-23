<?php

namespace App\View\Components\Bread\Input;

use Illuminate\View\Component;

class Select extends Component
{
    public function __construct(
        public ?string $name  = null,
        public ?string $value = null,
        public ?string $label = null,
        public array $options = [],
        public ?int $width = 12,
    )
    {}

    public function render()
    {
        return /** @lang Blade */
        <<<'blade'
          <div {{ $attributes->merge(['class' => 'form-group col-12 col-md-'.$width]) }}>
                <label for="data-{{$name}}">{{$label ?? Str::ucfirst($name)}}</label>
                 <select class="form-control @error($name) is-invalid @enderror" name="{{$name}}" id="data-{{$name}}">
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

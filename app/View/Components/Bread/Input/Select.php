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
        // add default = 1 if you want to default option appear
        public ?int $default = 0,
    )
    {}

    public function render()
    {
        return /** @lang Blade */
        <<<'blade'
          <div {{ $attributes->merge(['class' => 'form-group col-12 col-md-'.$width]) }}>
                <label for="data-{{$name}}">{{$label ?? Str::ucfirst($name)}}</label>
                 <select class="form-control @error($name) is-invalid @enderror" name="{{$name}}" id="data-{{$name}}" style="padding: .375rem 0.75rem !important;">
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

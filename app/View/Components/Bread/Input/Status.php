<?php

namespace App\View\Components\Bread\Input;

use Illuminate\View\Component;

class Status extends Component
{
    public function __construct(
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
                <p>{{$label}}</p>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="data-true" value="1" @if($value) checked @endif>
                    <label class="form-check-label" for="data-true">Active</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="status" id="data-false" value="0" @if(!$value) checked @endif>
                    <label class="form-check-label" for="data-false">Passive</label>
                </div>
            </div>
        blade;
    }
}

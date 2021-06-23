<?php

namespace App\View\Components\Bread\Input;

use Illuminate\View\Component;

class Relation extends Component
{
    public object $relations;

    public function __construct(
        public string $relation,
        public string $name,
        public string $show,
        public string $store = 'id',
        public ?string $value = null,
        public ?string $label = null,
        public ?int $width = 12,
        public array $excerpt = [],
        public bool $multiple = false,
    )
    {
        $this->relations = $relation::select([$show, $store])->orderBy($show)->limit(1000)->get();
    }

    public function render()
    {
        return /** @lang Blade */
        <<<'blade'
          <div {{ $attributes->merge(['class' => 'form-group col-12 col-md-'.$width]) }}>
                <label for="data-{{$name}}">{{$label ?? Str::ucfirst($name).' input'}}</label>
                 <select @if($multiple) multiple @endif type="text" class="form-control @error($name) is-invalid @enderror" name="{{$name}}" id="data-{{$name}}">
                        <option @if(is_null($value)) selected @endif  disabled >Select {{Str::lower($label)}}</option>
                        <option value="">--None--</option>
                        @foreach($relations as $parent)
                            @if(!in_array($parent->id, $excerpt))
                            <option @if($parent->id == $value) selected @endif value="{{ $parent->{$store} }}">{{ $parent->{$show} }}</option>
                            @endif
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

<?php

namespace App\View\Components\Bread\Input;

use Illuminate\View\Component;

class HasMany extends Component
{
    public object $relations;

    public function __construct(
        public string $relation,
        public string $name,
        public string $show,
        public string $store = 'id',
        public ?array $value = null,
        public ?string $label = null,
        public int $width = 12,
    )
    {
        $this->relations = $relation::select([$show, $store])->orderBy($show)->get();
    }

    public function render()
    {
        return /** @lang HTML */
        <<<'blade'
            <div {{ $attributes->merge(['class' => "form-group col-12 col-md-$width"]) }} >
                <label for="data-{{$name}}">{{$label ?? Str::ucfirst($name).' input'}}</label>
               <div class="p-2 border">
                @foreach($relations as $relation)
                <div class="form-check">
                  <input class="form-check-input" @if(in_array($relation->$store ,$value)) checked @endif id="{{$name}}-{{$relation->$store}}" type="checkbox" name="{{$name}}[]" value="{{$relation->$store}}">
                  <label class="form-check-label user-select-none" for="{{$name}}-{{$relation->id}}">
                    {{$relation->$show}}
                  </label>
                </div>
                @endforeach
               </div>
            </div>
        blade;
    }
}

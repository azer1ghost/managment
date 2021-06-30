<?php

namespace App\View\Components\Bread\Input;

use Illuminate\View\Component;

class Image extends Component
{
    public $width = null;
    public $label = null;
    public $value = null;
    public $name = null;
    public $placeholder = null;
    public $folder = "images";

    public function __construct(
        ?string $name  = null,
        ?string $value = null,
        ?string $label = null,
        ?string $placeholder = 'nophoto.png',
        ?int $width = 12
    )
    {
        $this->name = $name;
        $this->value = $value ?? $placeholder;
        $this->label = $label;
        $this->width = $width;
    }

    public function render()
    {
        return /** @lang Blade */
        <<<'blade'
          <div {{ $attributes->merge(['class' => 'form-group col-12 col-md-'.$width]) }}>
                <label for="data-{{$name}}">{{$label ?? Str::ucfirst($name)}}</label>
                <label for="data-{{$name}}">
                     <div class="card" style="max-width: 100%">
                         <img class="img-fluid" id="input-{{$name}}" src="{{asset("$folder/$value")}}" alt="{{$name}}"> 
                         <div class="btn btn-outline-primary">Change</div>
                     </div>
                </label>
                <input 
                       type="file"
                       accept="" 
                       class="form-control d-none @error($name) is-invalid @enderror"
                       name="{{$name}}" 
                       id="data-{{$name}}" 
                       value="{{$value ?? old($name)}}"
                       onchange="previewFile()"
                       >
                @error($name)
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <script>
           function previewFile() {
              const preview = document.querySelector('#input-{{$name}}');
              const file = document.querySelector('input[type=file]').files[0];
              const reader = new FileReader();
            
              reader.addEventListener("load", function () {
                // convert image file to base64 string
                preview.src = reader.result;
              }, false);
            
              if (file) {
                reader.readAsDataURL(file);
              }
            }
            </script>
        blade;
    }
}

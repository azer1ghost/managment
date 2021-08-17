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
                         <img class="img-fluid" id="input-{{$name}}" src="{{image($value)}}" alt="{{$name}}"> 
                         <div class="btn btn-outline-primary">Change</div>
                     </div>
                </label>
                <input 
                       type="file"
                       accept="image/" 
                       class="form-control d-none @error($name) is-invalid @enderror"
                       name="{{$name}}" 
                       id="data-{{$name}}"
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
              if (hasExtension('data-{{$name}}', ['.jpg', '.jpeg', '.gif', '.png'])) {
                  preview.src = URL.createObjectURL(file);
                  preview.onload = function() {
                    URL.revokeObjectURL(preview.src) // free memory
                  }
              }else{
                  $.alert({
                    title: 'Error',
                    content: 'Invalid image type, please upload png, jpg, jpeg or gif',
                    type: 'red',
                    icon: 'fa fa-times',
                    typeAnimated: true,
                    theme: 'modern'
                  }); 
              }
            }
            function hasExtension(inputID, exts) {
                const fileName = document.getElementById(inputID).value;
                return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName);
            }
            </script>
        blade;
    }
}

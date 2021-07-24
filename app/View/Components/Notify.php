<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Notify extends Component
{
    public function render()
    {
        return /** @lang Blade */
            <<<'blade'
         @if(session()->has('notify'))
             <script>
                $.alert({
                    title: '{!! session()->get('notify')['title']  !!}',
                    content: '{!! session()->get('notify')['message']  !!}',
                    type: '{{ session()->get('notify')['type'] }}',
                    typeAnimated: true,
                    theme: 'modern'
                });
            </script>
          @endif
        blade;
    }
}

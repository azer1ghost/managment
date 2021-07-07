<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LanguageBar extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function render()
    {
        return /** @lang HTML */
        <<<'blade'
            <div class="col-md-12 col-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    @foreach(config('app.locales') as $locale)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($loop->first) active @endif" id="{{$locale}}-tab" data-toggle="tab" href="#lang-{{$locale}}" role="tab" aria-controls="{{$locale}}" aria-selected="true">{{Str::ucfirst($locale)}}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        blade;
    }
}

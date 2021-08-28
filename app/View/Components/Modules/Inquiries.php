<?php

namespace App\View\Components\Modules;

use Illuminate\View\Component;

class Inquiries extends Component
{

    public function __construct()
    {
        //
    }

    public function render()
    {
        return view('panel.pages.cabinet.components.inquiries');
    }
}

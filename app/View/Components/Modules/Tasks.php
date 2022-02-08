<?php

namespace App\View\Components\Modules;

use Illuminate\View\Component;

class Tasks extends Component
{

    public function __construct()
    {
        //
    }

    public function render()
    {
        return view('pages.cabinet.components.tasks');
    }
}

<?php

namespace App\View\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class Permissions extends Component
{
    public ?Model $model;
    public ?string $action;

    public function __construct($action, $model)
    {
        $this->action = $action;
        $this->model = $model;
    }

    public function render()
    {
        return view('components.permissions');
    }
}

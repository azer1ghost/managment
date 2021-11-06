<?php

namespace App\View\Components;

use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class Resultable extends Component
{
    public ?string $model, $action, $method, $status = 'disable';
    public int $id;
    public ?Model $result;

    public function __construct($id, $model, $result, $status)
    {
        $this->id = $id;
        $this->model = $model;
        $this->result = $result;
        $this->method = is_null($this->result) ? null : 'PUT';
        $this->action = is_null($this->result) ? route('results.store', $id) : route('results.update', $this->result);
        $this->status = $status;
    }

    public function render()
    {
        return view('components.resultable');
    }
}

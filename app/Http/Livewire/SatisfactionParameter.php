<?php

namespace App\Http\Livewire;

use App\Models\Satisfaction;
use App\Models\Parameter;
use Livewire\Component;

class SatisfactionParameter extends Component
{
    public Satisfaction $data;
    public array  $satisfactionParameter;
    public array  $parameters;
    public ?string $action;

    public function mount()
    {
        $this->satisfactionParameter = $this->data->parameters()->get()->toArray() ?? [];
        $this->parameters = Parameter::pluck('name', 'id')->toArray();
    }

    public function addParameter()
    {
        $newArr = [
            "id" => null,
            'name' => null,
            'pivot' => [
                'satisfaction_id' => null,
                'parameter_id' => null,
                'ordering' => null,
            ]
        ];
        $this->satisfactionParameter[] = $newArr;
    }

    public function removeParameter($index)
    {
        unset($this->satisfactionParameter[$index]);
    }

    public function render()
    {
        return view('pages.satisfactions.components.satisfaction-parameter');
    }
}

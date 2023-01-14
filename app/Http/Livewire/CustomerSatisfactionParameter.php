<?php

namespace App\Http\Livewire;

use App\Models\CustomerSatisfaction;
use App\Models\Parameter;
use Livewire\Component;

class CustomerSatisfactionParameter extends Component
{
    public CustomerSatisfaction $data;
    public array  $customerSatisfactionParameter;
    public array  $parameters;
    public ?string $action;

    public function mount()
    {
        $this->customerSatisfactionParameter = $this->data->parameters()->get()->toArray() ?? [];
        $this->parameters = Parameter::pluck('name', 'id')->toArray();
    }

    public function addParameter()
    {
        $newArr = [
            "id" => null,
            'name' => null,
            'pivot' => [
                'customer_satisfaction_id' => null,
                'parameter_id' => null,
            ]
        ];
        $this->customerSatisfactionParameter[] = $newArr;
    }

    public function removeParameter($index)
    {
        unset($this->customerSatisfactionParameter[$index]);
    }

    public function render()
    {
        return view('pages.customer-satisfactions.components.service-parameter');
    }
}

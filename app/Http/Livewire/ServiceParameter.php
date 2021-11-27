<?php

namespace App\Http\Livewire;

use App\Models\Parameter;
use App\Models\Service;
use Livewire\Component;

class ServiceParameter extends Component
{
    public Service $data;
    public array  $serviceParameters;
    public array  $parameters;
    public ?string $action;

    public function mount()
    {
        $this->serviceParameters = $this->data->parameters()->get(['id','name'])->toArray() ?? [];
        $this->parameters = Parameter::pluck('name', 'id')->toArray();
    }

    public function addParameter()
    {
        $newArr = [
            "id" => null,
            'name' => null,
            'pivot' => [
                'service_id' => null,
                'parameter_id' => null,
                'show_in_table' => 0
            ]
        ];
        $this->serviceParameters[] = $newArr;
    }

    public function removeParameter($index)
    {
        unset($this->serviceParameters[$index]);
    }

    public function render()
    {
        return view('panel.pages.services.components.service-parameter');
    }
}

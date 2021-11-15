<?php

namespace App\Http\Livewire;

use App\Models\Client;
use App\Models\Department;
use App\Models\Service;
use App\Models\Work;
use Illuminate\Support\Collection;
use Livewire\Component;

class WorkForm extends Component
{
    public ?Work $data;
    public ?string $method, $action;
    public Collection $departments, $services, $users, $companies, $clients;
    public array $selected = [
        'department_id' => '',
        'service_id' => '',
        'user_id' => '',
        'client_id' => '',
    ];
    public array $workParameters = [];

    public Collection $parameters;

    public function getDepartmentProperty()
    {
        return Department::find($this->selected['department_id']);
    }

    public function updatedSelectedDepartmentId()
    {
        $this->selected['user_id'] = '';
    }

    public function mount()
    {
        $this->departments = Department::get(['id', 'name']);
        $this->services = Service::get(['id', 'name']);
        $this->clients = Client::get(['id', 'fullname']);

        foreach ($this->selected as $key => $selected) {
            $this->selected[$key] = request()->get($key) ?? optional($this->data)->getAttribute($key);
        }
        $this->getParameters();
    }

    public function getParameters()
    {
        $this->parameters = Service::find($this->selected['service_id'])->parameters;
        foreach (optional($this->data)->parameters ?? [] as $parameter) {
            $this->workParameters[$parameter->name] = $parameter->pivot->value;
        }
    }

    public function render()
    {
        return view('panel.pages.works.components.work-form');
    }
}

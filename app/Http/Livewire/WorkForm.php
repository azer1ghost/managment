<?php

namespace App\Http\Livewire;

use App\Models\Client;
use App\Models\Company;
use App\Models\CustomerCompany;
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
        'department_id' => null,
        'service_id' => null,
        'user_id' => null,
        'company_id' => null,
        'model' => 'client',
        'model_id' => null,
    ];
    public array $types = [
        'client',
        'company'
    ];

    public function getDepartmentProperty()
    {
        return Department::find($this->selected['department_id']);
    }

    public function updatedSelectedDepartmentId()
    {
        $this->selected['user_id'] = null;
    }

    public function updatedSelectedModel($type)
    {
        $this->clients = $type == 'client' ? Client::get(['id', 'name']) : CustomerCompany::get(['id', 'name']);
        $this->selected['model_id'] = null;
    }

    public function mount()
    {
        $this->departments = Department::get(['id', 'name']);
        $this->services = Service::get(['id', 'name']);
        $this->companies = Company::get(['id','name']);
        $this->clients = collect();

        foreach ($this->selected as $key => $selected) {
            $this->selected[$key] = optional($this->data)->getAttribute($key);
            if($key == 'model'){
                $this->updatedSelectedModel($this->selected[$key]);
            }
        }
    }

    public function render()
    {
        return view('panel.pages.works.components.work-form');
    }
}

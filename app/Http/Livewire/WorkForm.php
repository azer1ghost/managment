<?php

namespace App\Http\Livewire;

use App\Models\Department;
use App\Models\Service;
use App\Models\Work;
use App\Services\ExchangeRatesApi;
use Illuminate\Support\Collection;
use Livewire\Component;

class WorkForm extends Component
{
    public ?Work $data;
    public ?string $method, $action;
    public ?Collection $departments, $services, $users, $companies;
    public array $statuses;
    public array $selected = [
        'department_id' => '',
        'service_id' => '',
        'user_id' => '',
    ];
    public array $workParameters = [];

    public ?Collection $parameters;

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function mount()
    {
        $this->departments = Department::get(['id', 'name']);
        $this->services = Service::get(['id', 'name']);
        $this->statuses = Work::statuses();

        $user = auth()->user();
        if($user->hasPermission('canRedirect-work')) {
            $this->users = $this->department->users()->orderBy('name')->with('position')->isActive()->get(['id', 'name', 'surname', 'position_id', 'role_id']);
        }else {
            $this->users = collect([$user]);
        }

        foreach ($this->selected as $key => $selected) {
            if($key == 'department_id') {
                $this->selected['department_id'] = optional($this->data)->getAttribute($key) ?? request()->get('department_id') ?? $user->getAttribute('department_id');
                continue;
            }
            if($key == 'user_id') {
                $this->selected['user_id'] = optional($this->data)->getAttribute($key) ?? $user->getAttribute('id');
                continue;
            }
            $this->selected[$key] = request()->get($key) ?? optional($this->data)->getAttribute($key);
        }

        // check if user does not a department or service_id is not set from request
        abort_if(is_null($this->selected['service_id']) || is_null($this->selected['department_id']), 500);

        $this->getParameters();
    }

    public function getDepartmentProperty()
    {
        return Department::find($this->selected['department_id']);
    }

    public function getServiceProperty()
    {
        return Service::find($this->selected['service_id']);
    }

    public function getSubServicesProperty()
    {
        return $this->service->services;
    }

    public function getParameters()
    {
        $this->parameters = optional(Service::find($this->selected['service_id']))->parameters;
        foreach (optional($this->data)->parameters ?? [] as $parameter) {
            $this->workParameters[$parameter->name] = $parameter->pivot->value;
        }
    }

    public function render()
    {
        return view('panel.pages.works.components.work-form');
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\Department;
use App\Models\Service;
use App\Models\User;
use App\Models\Work;
use App\Services\ExchangeRatesApi;
use Illuminate\Support\Collection;
use Livewire\Component;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class WorkForm extends Component
{
    public ?Work $data;
    public ?string $method, $action;
    public ?Collection $departments, $services, $companies;
    public array $statuses, $users, $payment_methods;
    public array $selected = [
        'department_id' => '',
        'service_id' => '',
        'user_id' => '',
    ];
    public array $workParameters = [];

    public ?Collection $parameters;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function mount()
    {
        $this->departments = Department::get(['id', 'name']);
        $this->services = Service::get(['id', 'name']);
        $this->statuses = Work::statuses();
        $this->payment_methods = Work::paymentMethods();
        $user = auth()->user();
        $userModel = User::with('position')->find(auth()->id())->toArray();

        foreach ($this->selected as $key => $selected) {
            if($key == 'department_id') {
                $this->selected['department_id'] = optional($this->data)->getAttribute($key) ?? (request()->get('department_id') ?? $user->getAttribute('department_id'));
                continue;
            }

            if($key == 'service_id') {
                $this->selected['service_id'] = optional($this->data)->getAttribute($key) ?? request()->get('service_id');
                continue;
            }

            if($key == 'user_id') {
                $this->selected['user_id'] = optional($this->data)->getAttribute($key) ?? $user->getAttribute('id');
                continue;
            }

            $this->selected[$key] = request()->get($key) ?? optional($this->data)->getAttribute($key);
        }

        if($user->hasPermission('canRedirect-work')) {
            $this->users = $this->users()->isActive()->orderBy('name')->with('position')->get(['id', 'name', 'surname', 'position_id', 'role_id'])->toArray();
        }else {
            if ($this->method == 'POST') {
                $this->users = [$userModel];
            } else {
                $this->users = $this->selected['user_id'] ? [User::with('position')->find($this->selected['user_id'])->toArray()] : [];
            }
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
        return view('pages.works.components.work-form');
    }
}

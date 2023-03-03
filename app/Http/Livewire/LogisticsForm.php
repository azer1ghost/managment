<?php

namespace App\Http\Livewire;

use App\Models\Logistics;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class LogisticsForm extends Component
{
    public ?Logistics $data;
    public ?string $method, $action;
    public ?Collection $services;
    public array $statuses;
    public array $selected = [
        'service_id' => '',
        'user_id' => '',
    ];
    public array $logisticsParameters = [];

    public ?Collection $parameters;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function mount()
    {
        $this->services = Service::get(['id', 'name']);
        $this->statuses = Logistics::statuses();
        $user = auth()->user();

        foreach ($this->selected as $key => $selected) {
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

        // check if user does not a department or service_id is not set from request
        abort_if(is_null($this->selected['service_id']), 500);

        $this->getParameters();
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
            $this->logisticsParameters[$parameter->name] = $parameter->pivot->value;
        }
    }

    public function render()
    {
        return view('pages.logistics.components.logistics-form');
    }
}

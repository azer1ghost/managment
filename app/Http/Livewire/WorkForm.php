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
    public Collection $departments, $services, $users, $companies;
    public array $hardLevels;
    public array $statuses;
    public array $selected = [
        'department_id' => '',
        'service_id' => '',
        'user_id' => '',
    ];
    public array $workParameters = [];
    public string $earning;
    public float $rate;
    public string $currency;

    public Collection $parameters;

    public function getDepartmentProperty()
    {
        return Department::find($this->selected['department_id']);
    }

    public function updatedEarning()
    {
        $this->rate = $this->earning ? (new ExchangeRatesApi)->convert($this->currency, 'AZN', (float) $this->earning) : 0;
    }

    public function updatedCurrency()
    {
        $this->rate = $this->earning ? (new ExchangeRatesApi)->convert($this->currency, 'AZN', (float) $this->earning) : 0;
    }

    public function updatedSelectedDepartmentId()
    {
        $this->selected['user_id'] = '';
    }

    public function mount()
    {
        $this->departments = Department::get(['id', 'name']);
        $this->services = Service::get(['id', 'name']);

        $this->earning = optional($this->data)->getAttribute('earning') ?? '0';
        $this->rate = optional($this->data)->getAttribute('currency_rate') ?? 0;
        $this->currency = optional($this->data)->getAttribute('currency') ?? 'USD';
        $this->hardLevels = Work::hardLevels();
        $this->statuses = Work::statuses();

        $user = auth()->user();
        foreach ($this->selected as $key => $selected) {
            if($key == 'department_id'){
                $this->selected['department_id'] = optional($this->data)->getAttribute($key) ?? $user->getAttribute('department_id');
                continue;
            }
            if($key == 'user_id' && !$user->hasPermission('department-chief')){
                $this->selected['user_id'] = optional($this->data)->getAttribute($key) ?? auth()->id();
                continue;
            }
            $this->selected[$key] = request()->get($key) ?? optional($this->data)->getAttribute($key);
        }

        $this->getParameters();
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

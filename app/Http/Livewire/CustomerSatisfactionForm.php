<?php

namespace App\Http\Livewire;

use App\Models\CustomerSatisfaction;
use App\Models\Satisfaction;
use Illuminate\Support\Collection;
use Livewire\Component;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CustomerSatisfactionForm extends Component
{
    public ?CustomerSatisfaction $data;
    public ?string $method, $action;
    public ?Collection $satisfactions;
    public array $selected = [
        'url' => '',
    ];
    public array $customerSatisfactionParameters = [];

    public ?Collection $parameters;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function mount()
    {
        $this->satisfactions = Satisfaction::get(['id']);

        foreach ($this->selected as $key => $selected) {
            if($key == 'url') {
                $this->selected['url'] = optional($this->data)->getRelationValue('satisfaction')->getAttribute($key) ?? request()->get('url');
                continue;
            }
            $this->selected[$key] = request()->get($key) ?? optional($this->data)->getRelationValue('satisfaction')->getAttribute($key);
        }

        abort_if(is_null($this->selected['url']), 500);

        $this->getParameters();
    }

    public function getSatisfactionProperty()
    {
        return Satisfaction::where('url',$this->selected['url'])->first();
    }

    public function getParameters()
    {
        $this->parameters = optional(Satisfaction::where('url',$this->selected['url'])->first())->parameters;
        foreach (optional($this->data)->parameters ?? [] as $parameter) {
            $this->customerSatisfactionParameters[$parameter->name] = $parameter->pivot->value;
        }
    }

    public function render()
    {
        return view('pages.customer-satisfactions.components.customer-satisfaction-form');
    }
}

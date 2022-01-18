<?php

namespace App\Http\Livewire;

use App\Models\SalesActivity;
use Livewire\Component;

class ShowSalesSupplies extends Component
{
    public SalesActivity $salesActivity;
    public array $salesSupplies = [];
    public ?string $action;

    public function mount()
    {
        $this->salesSupplies = $this->salesActivity->salesSupplies()->get(['id', 'name', 'value'])->toArray();
    }

    public function addSupply()
    {
        $newArr = ["id" => null, "name" => null, "value" => null];
        $this->salesSupplies[] = $newArr;
    }

    public function removeSupply($index)
    {
        unset($this->salesSupplies[$index]);
    }

    public function render()
    {
        return view('pages.sales-activities.components.show-sales-supplies');
    }
}

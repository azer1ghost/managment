<?php

namespace App\Http\Livewire;

use App\Models\Evaluation;
use App\Models\SalesActivity;
use Livewire\Component;

class ShowEvaluations extends Component
{
    public Evaluation $supplier;
    public array $evaluations = [];
    public ?string $action;

    public function mount()
    {
        $this->evaluations = $this->supplier->evaluations()->get(['supplier_id', 'quality', 'delivery', 'distributor', 'availability', 'certificate', 'support', 'price', 'payment', 'returning', 'replacement',])->toArray();
    }

    public function addEvaluation()
    {
        $newArr = ["supplier_id" => null, "quality" => null, "delivery" => null, "distributor" => null, "availability" => null, "certificate" => null, "support" => null, "price" => null, "payment" => null, "returning" => null, "replacement" => null, ];
        $this->evaluations[] = $newArr;
    }

    public function removeEvaluation($index)
    {
        unset($this->evaluations[$index]);
    }

    public function render()
    {
        return view('pages.suppliers.components.show-evaluations');
    }
}

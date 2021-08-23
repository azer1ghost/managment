<?php

namespace App\Http\Livewire;

use App\Models\Option;
use App\Models\Parameter;
use Livewire\Component;

class ShowUserDefaults extends Component
{
    public ?int $user_id = null;
    public array  $defaults;
    public array  $arrOfColumns     = [];
    public array  $arrOfValues      = [];
    public array  $selectedColumns  = [];
    public array  $availableColumns = [];
    public bool   $all_selected     = false;
    public bool   $columnSelected   = true;

    public function mount()
    {
        $this->availableColumns = Parameter::select(['id'])->where('type', 'select')->pluck('id')->toArray();
        $this->defaults = auth()->user()->defaults()->get(['id', 'parameter_id', 'option_id'])->toArray();

        if($this->defaults){
            collect($this->defaults)->each(function($d, $idx){
                $this->addValue($idx, $d['parameter_id']);
                $this->arrOfColumns[$idx] = $this->addColumn();
                $this->selectedColumns[$idx] = (int) $d['parameter_id'];
            });
        }
    }

    public function addDefault()
    {
        // disable add button before some value is not selected
        $this->columnSelected = false;

        $newArr = ["id" => null, "parameter_id" => null, "option_id" => null];
        $this->defaults[] = $newArr;
        $count = count($this->defaults) - 1;
        $this->arrOfValues[$count] = [];
        $this->arrOfColumns[$count] = $this->addColumn();
    }

    public function changeOptions($id, $default_id)
    {
        $this->columnSelected = !($id == "null");

        $this->selectedColumns[$default_id] = (int) $id;

        $this->addValue($default_id, $id);
        $this->all_selected = empty(array_diff($this->availableColumns, $this->selectedColumns));
    }

    public function removeDefault($index)
    {
        unset($this->selectedColumns[$index], $this->defaults[$index], $this->arrOfColumns[$index], $this->arrOfValues[$index]);
    }

    public function addColumn()
    {
        return Parameter::query()
            ->where('type', 'select')
            ->whereNotIn('id', $this->selectedColumns)
            ->pluck('name', 'id')
            ->map(fn($p) => str_title($p))
            ->toArray();
    }

    public function addValue($index, $column)
    {
        $this->arrOfValues[$index] = Option::query()
            ->whereHas('parameters', fn($q) => $q->where('id', $column))
            ->pluck('text', 'id')
            ->toArray();
    }

    public function render()
    {
        return view('panel.pages.main.components.show-user-defaults');
    }
}

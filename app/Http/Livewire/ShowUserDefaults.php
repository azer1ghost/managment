<?php

namespace App\Http\Livewire;

use App\Models\Option;
use App\Models\Parameter;
use App\Models\User;
use Livewire\Component;

class ShowUserDefaults extends Component
{
    public ?User $user;
    public ?string $action;
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
        $this->defaults = $this->user ? $this->user->defaults()->get(['parameter_id', 'value'])->toArray() : [];

        if($this->defaults){
            collect($this->defaults)->each(function($d, $idx){
                $this->addValue($idx, $d['parameter_id']);
                $this->arrOfColumns[$idx] = $this->addColumn();
                $this->selectedColumns[$idx] = (int) $d['parameter_id'];
            });
        }

        if(count($this->availableColumns) == count($this->defaults)) $this->all_selected = true;
    }

    public function addDefault()
    {
        // disable add button before some value is not selected
        $this->columnSelected = false;

        $newArr = ["parameter_id" => null, "value" => null];
        $this->defaults[] = $newArr;
        $this->arrOfColumns[] = $this->addColumn();
        $this->arrOfValues[] = [];

    }

    public function changeOptions($id, $default_id)
    {
        $this->addValue($default_id, $id);
        $this->selectedColumns[$default_id] = (int) $id;

        // check if the last column is selected, then show add button
        $this->columnSelected = count($this->arrOfValues[array_key_last($this->arrOfValues)]) > 0;
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

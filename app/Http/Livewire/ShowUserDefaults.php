<?php

namespace App\Http\Livewire;

use App\Models\Option;
use App\Models\Parameter;
use Livewire\Component;

class ShowUserDefaults extends Component
{
    public ?int $user_id = null;
    public array  $defaults;
    public array $columns = [];
    public array $values = [];

    public function mount()
    {
        $this->columns = Parameter::query()
            ->where('type', 'select')
            ->pluck('name', 'id')
            ->map(fn($p) => str_title($p))
            ->toArray();
        $this->defaults = auth()->user()->defaults()->get()->toArray();
    }

    public function addDefault()
    {
        $newArr = ["id" => null, "parameter_id" => null, "option_id" => null];
        $this->defaults[] = $newArr;
    }

    public function changeOptions($id)
    {
        $this->values = Option::query()
            ->whereHas('parameters', fn($q) => $q->where('id', $id))
            ->pluck('text', 'id')
            ->toArray();
    }

    public function removeDefault($index)
    {
        unset($this->defaults[$index]);
    }

    public function render()
    {
        return view('panel.pages.main.components.show-user-defaults');
    }
}

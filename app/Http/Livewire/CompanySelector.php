<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Livewire\Component;

class CompanySelector extends Component
{

    public $companies = [];

    public function mount(){
        $this->companies = Company::all();
    }

    public function render()
    {
        return /** @lang Blade */
        <<<'blade'
          <div class="form-group col-6">
            <label for="exampleFormControlSelect1">Select Company</label>
            <select class="form-control" id="exampleFormControlSelect1">
                @foreach($companies as $key => $company)
                <option value="{{$company->key}}">{{$company->name}}</option>
                @endforeach
            </select>
          </div>
        blade;
    }

}

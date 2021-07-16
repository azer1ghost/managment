<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Parameter;
use Livewire\Component;

class CompanySelector extends Component
{
    public $data;

    public $companies;
    public $subjects;
    public $kinds;
    public $sources;
    public $contact_method;

    public $selectedCompany;
    public $selectedSubject;
    public $selectedKind;

    public function mount()
    {
        $this->companies = Company::whereNotIn('id', [1])->get();
        $this->subjects = collect();
        $this->kinds = collect();
        $this->sources = collect();
        $this->contact_method = Parameter::where('type', 'contact_method')->pluck('name','id')->toArray();
    }

    public function render()
    {
        return view('components.companySelector');
    }

    public function updatedSelectedCompany($id)
    {
        $parameters = Company::find($id)->parameters;

        $this->sources         = $parameters->where('type', 'source')->pluck('name', 'id');
        $this->subjects        = $parameters->where('type', 'subject');
        $this->kinds           = collect();
        $this->selectedKind    = null;
        $this->selectedSubject = null;
    }

    public function updatedSelectedSubject($id)
    {
        $this->kinds = Company::query()
            ->find($this->selectedCompany)
            ->parameters
            ->where('type', 'kind')
            ->where('parameter_id', $id);
    }

}

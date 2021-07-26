<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Parameter;
use Livewire\Component;

class InquiryForm extends Component
{
    public $data;

    public $action;
    public $method;
    public $companies;
    public $subjects;
    public $kinds;
    public $sources;
    public $statuses;
    public $contact_methods;

    public $selectedCompany;
    public $selectedSubject;
    public $selectedKind;

    public function mount()
    {
        $this->companies = Company::whereNotIn('id', [1])->get();
        $this->subjects = collect();
        $this->kinds = collect();
        $this->sources = collect();
        $this->contact_methods = Parameter::where('type', 'contact_method')->pluck('name','id')->toArray();
        $this->statuses = Parameter::where('type', 'status')->pluck('name','id')->toArray();
    }

    public function render()
    {
        return view('panel.pages.customer-services.inquiry.components.inquiry-form');
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
            //->with('parameters')
            ->find($this->selectedCompany)
            ->parameters
            ->where('type', 'kind')
            ->where('parameter_id', $id);
    }

}

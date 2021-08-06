<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Parameter;
use Livewire\Component;

class InquiryForm extends Component
{
    public $data;
    public $parameters;

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
        $this->parameters = Parameter::query()
            ->where('type', 'status')
            ->orWhere('type', 'contact_method')
            ->get();

        $this->companies = Company::with('parameters')->whereNotIn('id', [1])->get();

        $this->subjects = collect();
        $this->kinds    = collect();
        $this->sources  = collect();

        $this->statuses        = $this->parameters->where('type', 'status')->pluck('name', 'id')->toArray();
        $this->contact_methods = $this->parameters->where('type', 'contact_method')->pluck('name', 'id')->toArray();

        // selected
        if ($this->data){
            $this->updatedSelectedCompany($this->selectedCompany = $this->data->getAttribute('company_id'));
            $this->updatedSelectedSubject($this->selectedSubject = optional($this->data->getAttribute('subject'))->getAttribute('id'));
            $this->selectedKind = optional($this->data->getAttribute('kind'))->getAttribute('id');
        }
    }

    public function updatedSelectedCompany($id)
    {
        $this->parameters = $this->companies->find($id)->parameters;
        $this->subjects   = $this->parameters->where('type', 'subject');
        $this->sources    = $this->parameters->where('type', 'source')->pluck('name', 'id');
        $this->updatedSelectedSubject($this->selectedSubject);
    }

    public function updatedSelectedSubject($id)
    {
        $this->kinds = $this->parameters->where('type', 'kind')->where('parameter_id', $id);
    }

    public function render()
    {
        return view('panel.pages.customer-services.inquiry.components.inquiry-form');
    }
}

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

        $this->companies = Company::whereNotIn('id', [1])->get();

        $this->subjects = collect();
        $this->kinds    = collect();
        $this->sources  = collect();

        $this->statuses        = $this->parameters->where('type', 'status')->pluck('name', 'id')->toArray();
        $this->contact_methods = $this->parameters->where('type', 'contact_method')->pluck('name', 'id')->toArray();

        // selected
        if ($this->data){
            $this->updatedSelectedCompany($this->selectedCompany = $this->data->getAttribute('company_id'));
            $this->updatedSelectedSubject($this->selectedSubject = $this->data->getAttribute('subject')->getAttribute('id'));
            $this->selectedKind = optional($this->data->getAttribute('kind'))->getAttribute('id');
        }
    }

    public function render()
    {
        return view('panel.pages.customer-services.inquiry.components.inquiry-form');
    }

    public function updatedSelectedCompany($id)
    {
        $this->parameters = Company::with('parameters')->find($id)->parameters;
        $this->subjects  = $this->parameters->where('type', 'subject');
        $this->sources   = $this->parameters->where('type', 'source')->pluck('name', 'id');
    }

    public function updatedSelectedSubject($id)
    {
        $this->kinds = $this->parameters->where('type', 'kind')->where('parameter_id', $id);
    }

}

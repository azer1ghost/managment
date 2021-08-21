<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Inquiry;
use App\Models\Option;
use Illuminate\Support\Collection;
use Livewire\Component;

class InquiryForm extends Component
{
    protected $listeners = [
        'refreshInquiryForm' => '$refresh',
    ];

    public string $action;
    public string $method;

    public Inquiry $inquiry;
    public Company $company;

    public Collection $companies;
    public Collection $parameters;
    public Collection $mainParameters;

    public array $formFields;

    public array $filledFormFields;

    public array $selected = [
        'company' => null
    ];

    public function mount()
    {
        $this->companies = Company::with('parameters')->whereNotIn('id', [1])->get();

        $this->selected['company'] = $this->inquiry->getAttribute('company_id');

        $this->company = $this->companies->where('id', $this->selected['company'])->first();

        $this->updatedSelectedCompany($this->company->id);
    }

    public function updatedSelectedCompany($id)
    {
        $this->company = $this->companies->where('id', $id)->first();

        $this->mainParameters = $this->company
                                        ->parameters()
                                        ->with([
                                            'options' => fn($query) => $query->where('option_parameter.company_id', $id)
                                        ])
                                        ->whereNull('option_id')
                                        ->get();

        $this->formFields = $this->filledFormFields = $this->mainParameters->toArray();

        $this->fillFields();
    }

    public function updatedSelectedSubject($id)
    {
        $this->formFields = [];

        $subParameters = Option::find($id)
                                    ->subParameters()
                                    ->with([
                                        'options' => fn($query) => $query->where('option_parameter.company_id', $this->company->getAttribute('id'))
                                    ])
                                    ->get()
                                    ->toArray();

        if ($subParameters) {
            $this->formFields = array_merge($this->filledFormFields, $subParameters);
        } else {
            $this->formFields = $this->filledFormFields;
        }

        $this->fillFields();
    }

    protected function fillFields()
    {
        // TODO old values problem
        collect($this->formFields)->each(function ($param){
            $parameterOption = optional($this->inquiry->getParameter($param['name']));
            if ($param['type'] == 'select')
                $this->selected[$param['name']] = $parameterOption->getAttribute('id');
            else
                $this->selected[$param['name']] = $parameterOption->getAttribute('value');
        });
    }


    public function render()
    {
        return view('panel.pages.customer-services.inquiry.components.inquiry-form');
    }
}

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

    public array $hardFields;

    public array $cacheValues;

    public array $selected = [
        'company' => null
    ];

    public function mount()
    {
        $this->companies = Company::with('parameters')->whereNotIn('id', [1])->get();

        $this->selected['company'] = $this->inquiry->getAttribute('company_id');

        $this->company = $this->companies->where('id', $this->selected['company'])->first();

        $this->updatedSelectedCompany($this->selected['company']);

        $this->updatedSelectedSubject($this->selected['subject']);
    }

    public function updatedSelectedCompany($id)
    {
        $this->company = $this->companies->where('id', $id)->first();

        $this->mainParameters = $this->company
                                            ->parameters()
                                            ->whereNull('option_id')
                                            ->with([
                                                'options' => fn($query) => $query->where('option_parameter.company_id', $id)
                                            ])
                                            ->get();

        $this->formFields = $this->hardFields = $this->mainParameters->toArray();

        $this->cacheValues = [];

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
            $this->formFields = array_merge($this->hardFields, $subParameters);
        } else {
            $this->formFields = $this->hardFields;
        }

        array_multisort(array_column($this->formFields , 'order'), SORT_ASC, $this->formFields);

        $this->fillFields($subParameters);
    }

    protected function fillFields($subFields = null)
    {
        if(empty($this->cacheValues))
            $this->cacheValues($this->formFields);
        else
            $this->cacheValues($subFields);
    }

    protected function cacheValues(array $fields)
    {
        collect($fields)->each(function ($param){
            $parameterOption = optional($this->inquiry->getParameter($param['name']));
            if ($param['type'] == 'select') {
                $this->selected[$param['name']] = $parameterOption->getAttribute('id');
            } else {
                $this->selected[$param['name']] = $parameterOption->getAttribute('value');
            }
            $this->cacheValues[$param['name']] = $this->selected[$param['name']];
        });
    }

    public function render()
    {
        return view('panel.pages.customer-services.inquiry.components.inquiry-form');
    }
}

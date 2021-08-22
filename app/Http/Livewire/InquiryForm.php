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

    public array $cachedValues;

    public array $selected = [
        'company' => null
    ];

    public function mount()
    {
        $this->companies = Company::with('parameters')->whereNotIn('id', [1])->get();

        $this->selected['company'] = $this->inquiry->getAttribute('company_id');

        //don't need the one below?
        $this->company = $this->companies->where('id', $this->selected['company'])->first();

        $this->updatedSelectedCompany($this->selected['company']);

        // why? do we need to check if it is empty
        // one bug is that when we change company, subParameters don't appear
        if (isset($this->selected['subject'])){
            $this->updatedSelectedSubject($this->selected['subject']);
        }
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

        $this->cachedValues = [];

        $this->fillFields();
    }

    public function updatedSelectedSubject($id)
    {
        $subParameters = Option::find($id)
                                    ->subParameters()
                                    ->with([
                                        'options' => fn($query) => $query->where('option_parameter.company_id', $this->selected['company'])
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

    protected function fillFields($fields = null)
    {
        if (empty($this->cachedValues))
            $this->cacheValues($this->formFields);
        else
            $this->cacheValues($fields);
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

            $this->cachedValues[$param['name']] = $this->selected[$param['name']];
        });
    }

    public function render()
    {
        return view('panel.pages.customer-services.inquiry.components.inquiry-form');
    }
}

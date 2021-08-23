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

    public ?Inquiry $inquiry;

    public Collection $companies;
    public Collection $parameters;
    public Collection $mainParameters;

    public array $formFields = [];

    public array $defaultFields;

    public array $cachedValues;

    public array $selected = [
        'company' => null
    ];

    public function mount()
    {
        // TODO Hi, Elmin. There are numbered tasks for you. Good luck.

        // TODO 1 This query must be automatic
        $this->companies = Company::whereNotIn('id', [1])->get(); // $this->companies = Company::mustInquiring()->get();

        // TODO 1.1 add [mustInquiring bool] - column to company table checkbox to edit.blade
        // TODO 1.2 add scope [mustInquiring] to Company model
        // TODO 1.3 add checkbox to edit.blade ( Must Inquiring )
        // TODO 1.4 then call query like that $this->companies = Company::mustInquiring()->get();

        // TODO 2 modify parameters crud for current structure (name, type, ..., options -> like multiselect)
        // TODO 2.1 modify parameters -> add options selector (multi select)

        // TODO 3 create options crud

        // TODO creating new inquiry with user default inputs
        $this->updatedSelectedCompany($this->inquiry->getAttribute('company_id'));
    }

    public function updatedSelectedCompany($id)
    {
        $this->selected['company'] = $id;

        $this->mainParameters = $this->companies
                                        ->where('id', $id) // select currently company from collection
                                        ->first()
                                        ->parameters()
                                        ->whereNull('option_id')
                                        ->with([
                                            'options' => fn($query) => $query->where('option_parameter.company_id', $id)
                                        ])
                                        ->get();

        $this->formFields = $this->defaultFields = $this->mainParameters->toArray();

        $this->cachedValues = [];

        $this->fillFields();

        # The following called function must be upgrade to be able to manage all parameters
        if (isset($this->selected['subject'])) {
            $this->updatedSelectedSubject($this->selected['subject']);
        }
    }

    public function updatedSelectedSubject($id)
    {
        // TODO this function must be updated to be able to manage all parameters
        $subParameters = Option::find($id)
            ->subParameters()
            ->with(['options' => fn($query) => $query->where('option_parameter.company_id', $this->selected['company'])])
            ->get()->toArray();

        $this->formFields = array_merge($this->defaultFields, $subParameters);

        array_multisort(array_column($this->formFields , 'order'), SORT_ASC, $this->formFields);

        $this->fillFields($subParameters);
    }

    protected function fillFields($fields = null)
    {
       empty($this->cachedValues) ? $this->cacheValues($this->formFields) : $this->cacheValues($fields);
    }

    protected function cacheValues(array $fields)
    {
        collect($fields)->each(function ($param){
            $parameterOption = optional($this->inquiry->getParameter($param['name']));
            $this->selected[$param['name']] = ($param['type'] == 'select') ?
                $parameterOption->getAttribute('id') :
                $parameterOption->getAttribute('value');
        });
        $this->cachedValues = $this->selected;
    }

    public function render()
    {
        return view('panel.pages.customer-services.inquiry.components.inquiry-form');
    }
}

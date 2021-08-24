<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Inquiry;
use App\Models\Option;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class InquiryForm extends Component
{
    protected $listeners = [
        'refreshInquiryForm' => '$refresh',
    ];

    public ?string $action, $method;

    public Inquiry $inquiry;
    public Carbon $datetime;
    public Collection $companies, $parameters, $mainParameters;

    public array $formFields = [];

    public array $defaultFields, $cachedValues;

    public array $selected = [
        'company' => null
    ];

    public function mount()
    {
        $this->companies = Company::isInquirable()->get();

        $this->datetime = $this->inquiry->getAttribute('datetime') ?? now();

        $this->selected = $this->inquiry->getAttributes();

        dd($this->selected);

        $this->updatedSelectedCompany($this->selected['company']);
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

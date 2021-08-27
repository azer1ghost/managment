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
    public Collection $companies, $parameters;

    public array $defaultFields = [], $cachedValues = [], $formFields = [], $selected = [];

    public function mount()
    {
        $this->companies = Company::isInquirable()->get();

        $this->datetime = $this->inquiry->getAttribute('datetime') ?? now();

        if (in_array(auth()->user()->getAttribute('company_id'), $this->companies->pluck('id')->toArray()))
        {
            $this->updatedSelectedCompany(auth()->user()->getAttribute('company_id'));
        }
        elseif ($this->inquiry->getAttribute('company_id'))
        {
            $this->updatedSelectedCompany($this->inquiry->getAttribute('company_id'));
        }
    }

    public function updatedSelectedCompany($id)
    {
        $this->selected['company'] = $id;

        $this->defaultFields = $this->getMainParameters($id);

        $this->cachedValues = [];

        $this->pushFields();

        $this->fillFields();

        foreach ($this->selected as $name => $value){
            if ($name == 'company' || !is_numeric($value)) continue;
            $this->updatedSelected($value);
        }
    }

    public function updatedSelected($value)
    {
        $this->getSubFields($value);
    }

    protected function getSubFields($option_id)
    {
        $parameters = $this->getSubParameters($option_id);

        $this->pushFields($parameters, false);

        $this->fillFields($parameters);

        foreach ($parameters as $parameter) {

           // if (in_array($parameter['option_id'], $this->selected)){}

            if ($option_id != $parameter['option_id']) {
                foreach ($parameter['options'] as $option) {
                    $this->getSubFields($option['id']);
                }
            }
        }
    }

    public function pushFields(array $fields = [], $reset = true)
    {
        if ($reset){
            $this->formFields = array_merge($this->defaultFields, $fields);
        } else {
            $this->formFields = array_merge($this->formFields, $fields);
        }

        $this->sortFields();
    }

    protected function fillFields($fields = null)
    {
       empty($this->cachedValues) ? $this->cacheValues($this->formFields) : $this->cacheValues($fields);
    }

    protected function sortFields()
    {
        array_multisort(array_column($this->formFields , 'order'), SORT_ASC, $this->formFields);
    }

    protected function getMainParameters($company_id)
    {
        return $this->companies
            ->where('id', $company_id) // select currently company from collection
            ->first()
            ->parameters()
            ->whereNull('option_id')
            ->with([
                'options' => fn($query) => $query->where('option_parameter.company_id', $company_id)
            ])
            ->get()
            ->toArray();
    }

    protected function getSubParameters($option_id)
    {
      return Option::find($option_id)->subParameters()
            ->with(['options' => fn($query) => $query->where('option_parameter.company_id', $this->selected['company'])])
            ->get()
            ->toArray();
    }

    protected function cacheValues(array $fields)
    {
        collect($fields)->each(function ($param){

            $parameterOption = optional($this->inquiry->getParameter($param['name']));

            if ($param['type'] == 'select') {
                $this->selected[$param['name']] = $parameterOption->getAttribute('id') ?? auth()->user()->getUserDefault($param['name']);
            } else {
                $this->selected[$param['name']] = $parameterOption->getAttribute('value') ?? auth()->user()->getUserDefault($param['name']);
            }

        });
        $this->cachedValues = $this->selected;
    }

    public function render()
    {
        return view('panel.pages.customer-services.inquiry.components.inquiry-form');
    }
}

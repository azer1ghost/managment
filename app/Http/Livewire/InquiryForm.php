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

    public array $defaultFields, $cachedValues, $formFields, $selected;

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
            $this->updatedSelected($value, $name);
        }
    }

    public function updatedSelected($value, $name)
    {
        $parameters = $this->getSubParameters($value);

        $this->pushFields($parameters);

        $this->fillFields($parameters);
    }

    public function pushFields(array $fields = [])
    {
        $this->formFields = array_merge($this->defaultFields, $fields);

        // ordering by order column
        array_multisort(array_column($this->formFields , 'order'), SORT_ASC, $this->formFields);
    }

    protected function fillFields($fields = null)
    {
       empty($this->cachedValues) ? $this->cacheValues($this->formFields) : $this->cacheValues($fields);
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
            $this->selected[$param['name']] = ($param['type'] == 'select') ?
                $parameterOption->getAttribute('id') ?? auth()->user()->getUserDefault($param['name']):
                $parameterOption->getAttribute('value') ?? auth()->user()->getUserDefault($param['name']);
        });
        $this->cachedValues = $this->selected;
    }

    public function render()
    {

        dd($this->defaultFields);
        return view('panel.pages.customer-services.inquiry.components.inquiry-form');
    }
}

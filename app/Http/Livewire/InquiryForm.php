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

    public array $defaultFields = [], $subParametersArr = [], $cachedValues, $formFields = [];

    public array $selected = [
        'company' => null
    ];

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

        $this->subParametersArr = [];

        $this->fillFields();

        foreach ($this->selected as  $name => $value){
//            dd($value, $name);
            if ($name == 'company' || !is_numeric($value)) continue;
            $this->updatedSelected($value, $name);
        }
//        dd($this->subParametersArr);
    }

    public function updatedSelected($value, $name)
    {
        $subParameters = Option::find($value)
            ->subParameters()
            ->with(['options' => fn($query) => $query->where('option_parameter.company_id', $this->selected['company'])])
            ->get()
            ->toArray();

        if($subParameters) {
            $this->subParametersArr[$name] = $subParameters;
        }else{
            unset($this->subParametersArr[$name]);
        }

        $array = [];

        foreach ($this->subParametersArr as $value){
            foreach ($value as $v => $i){
                array_push($array, $i);
            }
        }

        $this->formFields = array_merge($this->defaultFields, $array);

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
                $parameterOption->getAttribute('id') ?? auth()->user()->getUserDefault($param['name']):
                $parameterOption->getAttribute('value') ?? auth()->user()->getUserDefault($param['name']);
        });
        $this->cachedValues = $this->selected;
    }

    public function render()
    {
        return view('panel.pages.customer-services.inquiry.components.inquiry-form');
    }
}

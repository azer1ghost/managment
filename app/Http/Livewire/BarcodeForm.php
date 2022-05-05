<?php

namespace App\Http\Livewire;

use App\Models\Barcode;
use App\Models\Company;
use App\Models\Option;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;


class BarcodeForm extends Component
{
    protected $listeners = [
        'refreshInquiryForm' => '$refresh',
    ];

    public ?string $action = null, $method = null;

    public ?Barcode $barcode;
    public ?string $code, $note, $customer, $phone;
    public Collection $companies, $parameters, $mediators;

    public array $cachedValues = [], $formFields = [], $selected = [];

    public function mount()
    {
        $this->companies = Company::isInquirable()->get();

        $this->note = $this->barcode->getAttribute('note');
        $this->code = $this->barcode->getAttribute('code');
        $this->customer = $this->barcode->getAttribute('customer');
        $this->phone = $this->barcode->getAttribute('phone');
        $this->mediators = User::get(['id', 'name', 'surname']);

        if ($this->barcode->getAttribute('company_id')) {
            $this->updatedSelectedCompany($this->barcode->getAttribute('company_id'));
        } elseif(is_numeric(request()->get('company')) && in_array(request()->get('company'), $this->companies->pluck('id')->toArray())) {
            $this->updatedSelectedCompany(request()->get('company'));
        } elseif(in_array(auth()->user()->getAttribute('company_id'), $this->companies->pluck('id')->toArray())) {
            $this->updatedSelectedCompany(auth()->user()->getAttribute('company_id'));
        } else {
            $this->selected['company'] = null;
        }

        $this->sortFields();
    }

    public function updatedSelectedCompany($id)
    {
        $this->selected['company'] = $id;

        $this->formFields = $this->getMainParameters($id);

        $this->cachedValues  = [];

        $this->fillFields();

        foreach ($this->selected as $name => $value) {
            if ($name == 'company' || !is_numeric($value)) continue;
            $this->updatedSelected($value, $name);
        }
    }

    public function updatingSelected($value, $name)
    {
        if ($name == 'company') return;

        $parameters = $this->getSubParameters($this->selected[$name]);

        $this->removeFormField($parameters);

        $this->loopSubParams($parameters, 'REMOVE');
    }

    public function updatedSelected($value)
    {
        if (empty($value)) return;

        $this->getSubFields($value);

    }

    protected function getSubFields($option_id)
    {
        $parameters = $this->getSubParameters($option_id);

        $this->pushFields($parameters);

        $this->fillFields($parameters);

        $this->loopSubParams($parameters);
    }

    public function removeFormField($params)
    {
        foreach ($params as $param) {
            unset($this->formFields[$param['name']]);
        }
    }

    protected function loopSubParams($params, $mode = 'ADD')
    {
        foreach ($params as $param) {
            foreach ($param['options'] as $option) {
                if ($option['id'] == $this->selected[$param['name']]) {
                    if ($mode == 'REMOVE') $this->updatingSelected($option['id'], $param['name']);
                    if ($mode == 'ADD')    $this->updatedSelected($option['id'], $param['name']);
                    break;
                }
            }
        }
    }

    public function pushFields(array $fields = [])
    {
        $this->formFields =  array_merge($this->formFields, $fields);
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

    protected function convertFieldsKeys($prevArr): array
    {
        $newArray = [];

        foreach ($prevArr as $field){
            $newArray[$field['name']] = $field;
        }

        return $newArray;
    }

    protected function getMainParameters($company_id): array
    {
        return  $this->convertFieldsKeys($this->companies
            ->where('id', $company_id) // select current company from collection
            ->first()
            ->parameters()
            ->when(!auth()->user()->isDeveloper() && $this->method == 'POST', function ($query){
                $query->where('company_parameter.department_id', auth()->user()->getAttribute('department_id'));
            })
            ->whereNull('option_id')
            ->with([
                'options' => fn($query) => $query->where('option_parameter.company_id', $company_id)
            ])
            ->get()
            ->toArray());
    }

    protected function getSubParameters($option_id): array
    {
        if (!Option::find($option_id)) return [];

        return $this->convertFieldsKeys(Option::find($option_id)
            ->subParameters()
            ->with(['options' => fn($query) => $query->where('option_parameter.company_id', $this->selected['company'])])
            ->get()
            ->toArray());
    }

    protected function cacheValues(array $fields)
    {
        collect($fields)->each(function ($param){

            $parameterOption = optional($this->barcode->getParameter($param['name']));

            if ($param['type'] == 'select') {
                $value = $parameterOption->getAttribute('id') ?? auth()->user()->getUserDefault($param['name']);
            } else {
                $value = $parameterOption->getAttribute('value') ?? auth()->user()->getUserDefault($param['name']);
            }

            $this->selected[$param['name']] = $value;
        });
        $this->cachedValues = $this->selected;
    }

    public function render()
    {
        return view('pages.barcode.components.barcode-form');
    }
}

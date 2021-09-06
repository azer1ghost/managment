<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Inquiry;
use App\Models\Option;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class InquiryForm extends Component
{
    protected $listeners = [
        'refreshInquiryForm' => '$refresh',
    ];

    public ?string $action, $method;

    public Inquiry $inquiry;
    public Carbon $datetime;
    public ?string $note;
    public Collection $companies, $parameters;

    public array $cachedValues = [], $formFields = [], $selected = [];

    public function mount()
    {
        $this->companies = Company::isInquirable()->get();

        $this->datetime = $this->inquiry->getAttribute('datetime') ?? now();
        $this->note = $this->inquiry->getAttribute('note');

        if ($this->inquiry->getAttribute('company_id')) {
            $this->updatedSelectedCompany($this->inquiry->getAttribute('company_id'));
        } elseif (in_array(auth()->user()->getAttribute('company_id'), $this->companies->pluck('id')->toArray())) {
            $this->updatedSelectedCompany(auth()->user()->getAttribute('company_id'));
        } else{
            $this->selected['company'] = null;
        }
    }

    public function updatedSelectedCompany($id)
    {
        $this->selected['company'] = $id;

        $this->formFields = $this->getMainParameters($id);

        $this->cachedValues  = [];

        $this->fillFields();

        foreach ($this->selected as $name => $value){
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
        foreach ($params as $param){
            unset($this->formFields[$param['name']]);
        }
    }

    protected function loopSubParams($params, $mode = 'ADD')
    {
        foreach ($params as $param){
            foreach ($param['options'] as $option){
                if ($option['id'] == $this->selected[$param['name']]){
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
            ->where('id', $company_id) // select currently company from collection
            ->first()
            ->parameters()
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

            $parameterOption = optional($this->inquiry->getParameter($param['name']));

            if ($param['type'] == 'select') {
                $value = $parameterOption->getAttribute('id') ?? auth()->user()->getUserDefault($param['name']);
            } else {
                $value = $parameterOption->getAttribute('value') ?? auth()->user()->getUserDefault($param['name']);
            }

            $this->selected[$param['name']] = $value;
        });
        $this->cachedValues = $this->selected;
    }

    /* Api functions */
    public function updatedSelectedClientCode($value)
    {
        $client_code = strtoupper($value);

        $prefix = 'MBX';

        $client_code = str_starts_with($client_code, $prefix) ? $client_code : $prefix.$value;

        $apiURL = "http://api.mobex.az/v1/user/search?token=884h7d345&value={$client_code}&key=customer_id";

        $response = Http::get($apiURL)->json();

        if(!isset($response['errors'])){

            $this->selected['fullname'] = $response['full_name'];

            $this->selected['phone'] = $response['phone'];

            $this->selected['email'] = $response['email'];

            $this->formFields['client_code']['class'] = $this->formFields['fullname']['class'] = $this->formFields['phone']['class'] = "is-valid";

        }
        else{
            $this->formFields['client_code']['class'] = "is-invalid";
            $this->formFields['client_code']['message'] = $response['errors'];
            $this->selected['fullname'] = null;
            $this->selected['phone'] = null;
            $this->formFields['fullname']['class'] = "is-invalid";
            $this->formFields['phone']['class'] = "is-invalid";
        }

        ///MBX33291
    }
    /* end Api functions */

    public function render()
    {
        return view('panel.pages.inquiry.components.inquiry-form');
    }

}

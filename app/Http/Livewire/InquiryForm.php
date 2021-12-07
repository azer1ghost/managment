<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Inquiry;
use App\Models\Option;
use App\Services\MobexApi;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class InquiryForm extends Component
{
    protected $listeners = [
        'refreshInquiryForm' => '$refresh',
    ];

    public ?string $action = null, $method = null;

    public Inquiry $inquiry;
    public Carbon $datetime;
    public ?string $note;
    public bool $isRedirected = false;
    public Collection $companies, $parameters;

    public array $cachedValues = [], $formFields = [], $selected = [];

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function mount()
    {
        $this->companies = Company::isInquirable()->get();

        $this->datetime = $this->inquiry->getAttribute('datetime') ?? now();
        $this->note = $this->inquiry->getAttribute('note');

        if ($this->inquiry->getAttribute('company_id')) {
            $this->updatedSelectedCompany($this->inquiry->getAttribute('company_id'));
        } elseif(is_numeric(request()->get('company')) && in_array(request()->get('company'), $this->companies->pluck('id')->toArray())) {
            $this->updatedSelectedCompany(request()->get('company'));
        } elseif(in_array(auth()->user()->getAttribute('company_id'), $this->companies->pluck('id')->toArray())) {
            $this->updatedSelectedCompany(auth()->user()->getAttribute('company_id'));
        } else {
            $this->selected['company'] = null;
        }

        $this->selected['is_out'] = is_null($this->inquiry->getAttribute('is_out')) ? 0 : $this->inquiry->getAttribute('is_out');

        if(array_key_exists('status', $this->selected) && $this->selected['status'] == Inquiry::REDIRECTED) {
            $this->isRedirected = true;
        }
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

    public function updatedSelected($value, $name)
    {
        $this->getSubFields($value);

        if (in_array($name, ['customer_id', 'phone', 'email']) && $this->selected['company'] == 4) {
            $this->apiForMobexFields($value, $name);
        }
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
            ->when(!auth()->user()->isDirector() || !auth()->user()->isDeveloper(), function ($query){
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

    public function apiForMobexFields($value, $name)
    {
        $response = (new MobexApi)->by($name)->value($value)->get();

        $fields = ['fullname', 'phone', 'email', 'customer_id'];

        if(!isset($response['errors'])) {
            foreach ($fields as $field) {
                $this->selected[$field] = $response[$field];
                $this->formFields[$field]['class'] = "is-valid";
            }
        }
        else{
            foreach ($fields as $field) {
                $this->formFields[$field]['class'] = "is-invalid";
            }
            $this->formFields['customer_id']['message'] = $response['errors'];
        }
    }

    public function render()
    {
        return view('panel.pages.inquiry.components.inquiry-form');
    }

}

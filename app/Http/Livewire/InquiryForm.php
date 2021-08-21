<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Inquiry;
use App\Models\Option;
use App\Models\Parameter;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Component;

class InquiryForm extends Component
{
    public Inquiry $inquiry;
    public Company $company;

    public Collection $companies;
    public Collection $parameters;
    public Collection $mainParameters;
    public array $formFields;

    public string $action;
    public string $method;

    protected $listeners = [
        'refreshInquiryForm' => '$refresh',
    ];

    public array $selected = [
        'company' => null
    ];

    public function mount()
    {
        $this->companies = Company::with('parameters')->whereNotIn('id', [1])->get();

        $this->selected['company'] = $this->inquiry->getAttribute('company_id');

        $this->company = $this->companies->where('id', $this->selected['company'])->first();

        $this->updatedSelectedCompany($this->company->id);
    }

    public function updatedSelectedCompany($id)
    {
        $this->company = $this->companies->where('id', $id)->first();

        $this->mainParameters = $this->company
                                        ->parameters()
                                        ->with(['options' => fn($query) => $query->where('option_parameter.company_id', $this->company->id) ])
                                        ->whereNull('option_id')
                                        ->get();

        $this->formFields = $this->mainParameters->toArray();

        $this->proceed();

       // dd($this->selected);
    }

    public function updatedSelectedSubject($id)
    {

        $subParameters = Option::find($id)->subParameters()->with(['options' => fn($query) => $query->where('option_parameter.company_id', $this->company->id) ])->get()->toArray();

        if($subParameters){

//            foreach ($subParameters as $subParam){
//                unset($this->formFields[$subParam['name']]);
//            }

            $this->formFields = array_merge($this->formFields, $subParameters);
        }

       // $this->selected['kind'] = null;

       //dd($this->formFields);

    }

    protected function proceed()
    {
        collect($this->formFields)->each(function ($param){
            $parameterOption = optional($this->inquiry->getParameter($param['name']));
            if ($param['type'] == 'select')
                $this->selected[$param['name']] = $parameterOption->getAttribute('id');
            else
                $this->selected[$param['name']] = $parameterOption->getAttribute('value');
        });
    }


    public function render()
    {
        //dd($this->formFields);
        return view('panel.pages.customer-services.inquiry.components.inquiry-form');
    }
}

<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Inquiry;
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

        $this->proceed();
    }

    public function updatedSelected($id)
    {
        $this->company = $this->companies->where('id', $id)->first();
        $this->proceed();
    }

    protected function proceed()
    {
        //dd($this->company->parameters);
    }


    public function render()
    {
        return view('panel.pages.customer-services.inquiry.components.inquiry-form');
    }
}

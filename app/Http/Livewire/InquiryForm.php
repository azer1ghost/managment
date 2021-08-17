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
    public ?object $data;
    public ?object $inquiry;
    public Collection $parameters;
    public User $user;

    public string $action;
    public string $method;

    public Collection $companies;
    public Collection $subjects;
    public Collection $kinds;
    public Collection $sources;
    public array $statuses;
    public array $contact_methods;

    public ?int $selectedCompany = null;
    public ?int $selectedSubject = null;
    public ?int $selectedKind = null;

    protected $listeners = [
        'refreshInquiryForm' => '$refresh',
    ];

    public function mount()
    {
        $user = auth()->user();

        if (is_null($this->data)){
            $this->inquiry = new Inquiry([
                'contact_method' => $user->getDefault('contact_method'),
                'datetime' => now(),
                'company_id' => $user->getDefault('company_id'),
                'client' => "MBX",
                'subject' => null,
                'kind' => null,
                'fullname' => null,
                'phone' => null,
            ]);
        } else {
            $this->inquiry = $this->data;
        }

        $this->parameters = Parameter::query()
            ->where('type', 'status')
            ->orWhere('type', 'contact_method')
            ->get();

        $this->companies = Company::with('parameters')->whereNotIn('id', [1])->get();

        $this->subjects = collect();
        $this->kinds    = collect();
        $this->sources  = collect();

        $this->statuses        = $this->parameters->where('type', 'status')->pluck('name', 'id')->toArray();
        $this->contact_methods = $this->parameters->where('type', 'contact_method')->pluck('name', 'id')->toArray();

        // selected
        if ($this->data){
            $this->selectedSubject = $this->data->getAttribute('subject');
            $this->selectedKind = $this->data->getAttribute('kind');
            $this->updatedSelectedCompany($this->selectedCompany = $this->data->getAttribute('company_id'));
        }
    }

    public function updatedSelectedCompany($id)
    {
        $this->parameters = $this->companies->find($id)->parameters;
        $this->subjects   = $this->parameters->where('type', 'subject')->pluck('name', 'id');
        $this->sources    = $this->parameters->where('type', 'source')->pluck('name', 'id');
        $this->updatedSelectedSubject($this->selectedSubject);
    }

    public function updatedSelectedSubject($id)
    {
        $this->kinds = $this->parameters->where('type', 'kind')->where('parameter_id', $id);
    }

    public function render()
    {
        return view('panel.pages.customer-services.inquiry.components.inquiry-form');
    }
}

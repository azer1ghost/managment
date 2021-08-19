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
    public int $inquiry_id;

    public Inquiry $inquiry;

    public Collection $companies;
    public Collection $parameters;

    public string $action;
    public string $method;

    protected $listeners = [
        'refreshInquiryForm' => '$refresh',
    ];

    public array $selected = [
        'company_id' => null
    ];

    public function mount()
    {
        $this->inquiry = Inquiry::find($this->inquiry_id);

        $user = auth()->user();

        $this->companies = Company::with('parameters')->whereNotIn('id', [1])->get();

        $this->selected['company_id'] = $this->inquiry->company_id;



    }

    public function render()
    {
        return view('panel.pages.customer-services.inquiry.components.inquiry-form');
    }
}

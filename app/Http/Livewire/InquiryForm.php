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
    public ?object $inquiry;

    public Collection $companies;
    public Collection $parameters;

    public string $action;
    public string $method;

    protected $listeners = [
        'refreshInquiryForm' => '$refresh',
    ];

    public function mount()
    {
        $user = auth()->user();

        $this->companies = Company::with('parameters')->whereNotIn('id', [1])->get();
    }

    public function render()
    {
        return view('panel.pages.customer-services.inquiry.components.inquiry-form');
    }
}

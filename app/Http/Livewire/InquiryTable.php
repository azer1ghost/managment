<?php

namespace App\Http\Livewire;

use App\Models\Inquiry;
use Livewire\Component;
use Livewire\WithPagination;

class InquiryTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        return view('panel.pages.customer-services.inquiry.components.inquiry-table', [
            'inquiries' => Inquiry::query()
                ->select('id', 'user_id', 'date', 'time', 'company_id', 'fullname', 'subject')
                ->with([
                    'company' => function ($query){
                        $query->select('id','name');
                    }
                ])
                ->latest()
                ->simplePaginate(10)
        ]);
    }
}

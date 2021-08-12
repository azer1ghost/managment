<?php

namespace App\Http\Livewire;

use App\Models\Inquiry;
use App\Models\Parameter;
use Livewire\Component;
use Livewire\WithPagination;

class InquiryTable extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public $subjects;
    public $companies;
    public $kinds;

    public $filteredSubjects;
    public $filteredCompanies;
    public $filteredKinds;

    public array $filters = [
        'search' => null,
        'subjects' => [],
        'companies' => [],
        'kinds' => [],
    ];

    public function mount()
    {
        $this->subjects = Parameter::where('type', 'subject')->get();
    }

    public function render()
    {
        return view('panel.pages.customer-services.inquiry.components.inquiry-table', [
            'inquiries' => Inquiry::query()
                ->whereNull('inquiry_id')
                ->select('id', 'code', 'user_id', 'datetime', 'company_id', 'fullname', 'subject')
                ->where(function ($query)  {
                    foreach ($this->filters as $column => $value) {
                        $query->when($value, function ($query, $value) use ($column) {
                            if(is_array($value)) {
                                $query->whereIn(\Str::singular($column), $value);
                            }
                            elseif ($column === 'search') {
                                $query->where('code', 'like', "%$value%");
                            }
                            else {
                                $query->where(\Str::singular($column), $value);
                            }
                        });
                    }
                })
                ->with([
                    'company' => function ($query){
                        $query->select('id','name');
                    }
                ])
                ->latest('datetime')
                ->simplePaginate(10)
        ]);
    }
}


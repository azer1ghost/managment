<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Inquiry;
use App\Models\Parameter;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class InquiryTable extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public $subjects;
    public $companies;
    public $kinds;

    public array $filters = [
        'code' => null,
        'datetime' => null,
        'subjects' => [],
        'company_id' => [],
        'kinds' => [],
    ];

    public string $daterange;

    public function mount()
    {
        $this->daterange = implode(' - ', [now()->firstOfMonth()->format('d/m/Y'), now()->format('d/m/Y')]);
        $this->subjects  = Parameter::where('type', 'subject')->get();
        $this->companies = Company::whereNotIn('id', [1])->get();
    }

    public function render()
    {
        return view('panel.pages.customer-services.inquiry.components.inquiry-table', [
            'inquiries' => Inquiry::query()
                ->whereNull('inquiry_id')
                ->select('id', 'code', 'user_id', 'datetime', 'company_id', 'fullname', 'subject')
                ->when($this->daterange, function ($query, $daterange) {
                    [$from, $to] = explode(' - ', $daterange);
                    $query->whereBetween('datetime', [Carbon::createFromFormat('d/m/Y', $from), Carbon::createFromFormat('d/m/Y', $to)]);
                })
                ->where(function ($query)  {
                    foreach ($this->filters as $column => $value) {
                        $query->when($value, function ($query, $value) use ($column) {
                            if (is_array($value)) {
                                $query->whereIn(\Str::singular($column), $value);
                            }
                            elseif (strtotime($value)) {
                                $query->whereDate($column, $value);
                            }
                            elseif (is_numeric($value)) {
                                $query->where(\Str::singular($column), $value);
                            }
                            elseif (is_string($value)) {
                                $query->where(\Str::singular($column), 'like', "%$value%");
                            }
                            else {
                                $query->where(\Str::singular($column), $value);
                            }
                        });
                    }
                })
                ->with([
                    'company' => function ($query){
                        $query->select('id', 'name');
                    }
                ])
                ->latest('datetime')
                ->simplePaginate(10)
        ]);
    }


    protected function updatedDaterange($value)
    {
        //dd($value);
    }

}


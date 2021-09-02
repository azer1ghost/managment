<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Inquiry;
use App\Models\Option;
use App\Models\Parameter;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class InquiryTable extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public bool $trashBox = false;

    public $subjects;
    public $statuses;
    public $companies;
    public $kinds;

    public array $filters = [
        'code'       => null,
        'datetime'   => null,
        'company_id' => [],
    ];

    public array $parameterFilters = [
        'subjects'   => [],
        'kinds'      => [],
        'status'     => [],
        'client_code' => '',
        'fullname' => ''
    ];

    public string $daterange;

    public array $range = [
        'from' => null,
        'to'   => null
    ];

    public function mount()
    {
        $this->updateDaterange($this->daterange = implode(' - ', [now()->firstOfMonth()->format('d/m/Y'), now()->format('d/m/Y')]));

        $this->subjects  = Parameter::where('name', 'subject')->first()->options->unique();
        $this->statuses  = Parameter::where('name', 'status')->first()->options->unique();
        $this->companies = Company::whereNotIn('id', [1])->get();
    }

    public function filter()
    {
        $this->updateDaterange($this->daterange);
        $this->render();
    }

    public function canViewAll(): bool
    {
        $user = auth()->user();
        return $user->isDeveloper() || $user->isAdministrator() || $user->role->hasPermission('viewAll-inquiry');
    }

    public function render()
    {
        return view('panel.pages.inquiry.components.inquiry-table', [
            'inquiries' => Inquiry::query()
                ->withoutBackups()
                ->when(!$this->canViewAll(), function ($query){
                    return $query->where('user_id', auth()->id());
                })
                //->select('id', 'code', 'user_id', 'datetime', 'company_id', 'fullname', 'subject', 'created_at')
                ->when($this->trashBox, fn($query) => $query->onlyTrashed())
                ->whereBetween('datetime', [$this->range['from'], $this->range['to']])
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
                ->where(function ($query)  {
                    foreach ($this->parameterFilters as $column => $value) {
                        $query->when($this->parameterFilters[$column], function ($query) use ($column, $value){
                            if (is_array($value)) {
                                $query->whereHas('parameters', function ($query) use ($column) {
                                    $query->whereIn('inquiry_parameter.value', $this->parameterFilters[$column]);
                                });
                            } else {
                                $query->whereHas('parameters', function($query) use ($value) {
                                    $query->where('inquiry_parameter.value', 'LIKE', "%{$value}%");
                                });
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
                ->paginate(10)
        ]);
    }

    protected function updateDaterange($value)
    {
        [$from, $to] = explode(' - ', $value);

        $this->range['from'] = Carbon::createFromFormat('d/m/Y', $from)->startOfDay();
        $this->range['to']   = Carbon::createFromFormat('d/m/Y', $to)->endOfDay();
    }
}


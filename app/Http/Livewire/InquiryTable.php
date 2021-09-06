<?php

namespace App\Http\Livewire;

use App\Models\Company;
use App\Models\Inquiry;
use App\Models\Option;
use App\Models\Parameter;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class InquiryTable extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    protected $listeners = ['statusChanged' => 'updateInquiryStatus', 'updateFilter' => 'updateFilter'];

    public bool $trashBox = false;

    public $subjects;
    public $statuses;
    public $companies;
    public $kinds;
    public $users;
    public $statusParameterId;

    public array $filters = [
        'code'       => null,
        'company_id' => [],
        'user_id' => [],
    ];

    public array $parameterFilters = [
        'subjects'   => [],
        'kinds'      => [],
        'status'     => [],
        'client_code' => '',
        'search_client' => '',
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

        $this->statusParameterId = Parameter::where('name', 'status')->first()->getAttribute('id');

        $this->companies = Company::whereNotIn('id', [1])->get();

        $this->users = User::has('inquiries')->get(['id', 'name', 'surname']);
    }

    public function updateFilter()
    {
        $this->updateDaterange($this->daterange);
        $this->render();
    }

    public function updateInquiryStatus($inquiry_id, $oldVal, $val)
    {
        $inquiry = Inquiry::find($inquiry_id);

        $inquiry->parameters()->detach($this->statusParameterId);

        $inquiry->parameters()->attach($this->statusParameterId, ['value' => $val]);

        $oldOption = $oldVal ? Option::where('id', $oldVal)->first()->getAttribute('text') : __('translates.filters.select');

        $newOption = $val    ? Option::where('id', $val)->first()->getAttribute('text')    : __('translates.filters.select');

        $this->dispatchBrowserEvent(
            'alert', [
                'type'    => 'blue',
                'title'   =>  __('translates.flash_messages.inquiry_status_updated.title', ['code' => $inquiry->getAttribute('code')]),
                'message' =>  __('translates.flash_messages.inquiry_status_updated.msg',   ['prev' => $oldOption, 'next' => $newOption])]);
    }

    public function render()
    {
        return view('panel.pages.inquiry.components.inquiry-table', [
            'inquiries' => Inquiry::query()
                ->withoutBackups()
                ->when(!Inquiry::userCanViewAll(), function ($query){
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


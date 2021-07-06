<?php

namespace App\Http\Controllers\Platform\Modules;

use App\Http\Controllers\Controller;
use App\Models\CallCenter;
use App\Models\Company;
use App\Models\Sustainable\Sources;
use App\Models\Sustainable\Statuses;
use App\Models\Sustainable\Subjects;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CallCenterController extends Controller
{
    protected $rules = [
        'date' => 'required',
        'time'  => "required",
        'client'  => "string|max:255",
        'fullname'  => "string|max:255",
        'phone'  => "string|max:255",
        'subject'  => "required|string|max:255",
        'source'  => "required|string|max:255",
        'note'  => "string|max:255",
        'redirected'  => "string|max:255",
        'status' => "required|string",
        'company_id'  => "required|int|max:11",
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('panel.pages.customer-services.call-center.index')->with([
            "companies" => Company::select(['id','name'])->pluck('name','id')->toArray(),
            "subjects"  => Subjects::get()->toArray(),
            "sources"   => Sources::get()->toArray(),
            "statuses"  => Statuses::get()->toArray(),
        ]);
    }

    public function table(Request $request): JsonResponse
    {
        $limit  = $request->get('limit')  ?? 25;
        $offset = $request->get('offset') ?? 0;
        $search = $request->get('search');
        $sort   = $request->get('sort')   ?? 'created_at';
        $order  = $request->get('order')  ?? 'asc';

        $filterBySubject = $request->get('subject');
        $filterByDate    = $request->get('date');

        $query = CallCenter::query();

        $query->latest()->orderBy($sort, $order);

        if ($filterBySubject){
            $query->whereIn('subject', $filterBySubject);
        }

        if ($filterByDate){
            $query->whereDate('date', $filterByDate);
            //whereBetween('reservation_from', [$from, $to]);
        }

        if ($search) {
            $query
                ->where('note', 'like', "%$search%")
                ->orWhere('fullname', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%")
                ->orWhere('user', 'like', "$search%");
        }

        $total = $query->count();

        // define query offset and limit (for pagination of table)
        if ($offset) {
            $query->offset($offset);
        }
        if ($limit) {
            $query->limit($limit);
        }

        $data = $query->get();

        //data row mutator or modifier
        $rows = $data->map(function ($row){
            $row->subject = Subjects::get()->toArray()[$row->subject];
            $row->fullname = ucfirst($row->fullname);
            $row->editable = true;
            return $row;
        });

        return response()->json([
            'total' => $total,
            'rows'  => $rows,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);

        $userID = auth()->id();

        $data = CallCenter::create( array_merge(
            $validated,
            ['user_id' => $userID]
        ));

        Log::channel('daily')->info("New request created by user #ID:$userID ".json_encode($data));

        return back()->with(['notify' => ['type' => 'success', 'message' => 'Created successfully']]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CallCenter $callCenter)
    {
        return view('panel.pages.customer-services.call-center.edit')->with([
            "companies"  => Company::select(['id','name'])->pluck('name','id')->toArray(),
            "subjects"   => Subjects::get()->toArray(),
            "sources"    => Sources::get()->toArray(),
            "statuses"   => Statuses::get()->toArray(),
            "callCenter" => $callCenter,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CallCenter $callCenter): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate($this->rules);

        $callCenter->update($validated);

        $userID = auth()->id();

        Log::channel('daily')->info("Request update by user #ID:$userID ".json_encode($callCenter->getChanges()) );

        return back()->with(['notify' => ['type' => 'success', 'message' => 'Updated successfully']]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}

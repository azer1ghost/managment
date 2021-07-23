<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\InquiryRequest;
use App\Models\{Company, Inquiry, Role};
use Illuminate\Http\{JsonResponse, RedirectResponse, Request};
use Illuminate\Support\Facades\{Gate, Log};

class InquiryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response('inquiry');

//        return Inquiry::query()
//            ->with(array(
//                'company' => function($query) {
//                    $query->select('id','name');
//                },
//                'user'
//            ))
//            ->get()
//            ->toJson();

//        dd(Subjects::get()->toArray());

//        return view('panel.pages.customer-services.inquiry.index')->with([
//            "companies" => Company::with('parameters')->whereNotIn('id', [1])->select(['id','name'])->pluck('name','id')->toArray(),
//            "operators"  => Role::whereIn('key', ['developer', 'call-center-operator'])->first()->users->pluck('name','phone')->toArray(), //call-center-operator
//            "subjects"  => Subjects::get()->toArray(),
//            "sources"   => Sources::get()->toArray(),
//            "statuses"  => Statuses::get()->toArray(),
//        ]);

    }
//
//    public function table(InquiryRequest $request): JsonResponse
//    {
//        $limit  = $request->get('limit')  ?? 25;
//        $offset = $request->get('offset') ?? 0;
//        $search = $request->get('search');
//        $sort   = $request->get('sort')   ?? 'created_at';
//        $order  = $request->get('order')  ?? 'asc';
//
//        $filterBySubject = $request->get('subject');
//        $filterByDate    = $request->get('date');
//
//        $query = Inquiry::query();
//
//        $query->orderBy($sort, $order)->latest();
//
//        if ($filterBySubject){
//            $query->whereIn('subject', $filterBySubject);
//        }
//
//        if ($filterByDate){
//            $query->whereDate('date', $filterByDate);
//            //whereBetween('reservation_from', [$from, $to]);
//        }
//
//        if ($search) {
//            $query
//                ->where('note', 'like', "%$search%")
//                ->orWhere('fullname', 'like', "%$search%")
//                ->orWhere('phone', 'like', "%$search%")
//                ->orWhere('user', 'like', "$search%");
//        }
//
//        $total = $query->count();
//
//        // define query offset and limit (for pagination of table)
//        if ($offset) {
//            $query->offset($offset);
//        }
//        if ($limit) {
//            $query->limit($limit);
//        }
//
//        $data = $query->get();
//
//        //data row mutator or modifier
//        $rows = $data->map(function ($row){
//            $row->subject = Subjects::get()->toArray()[$row->subject];
//            $row->fullname = ucfirst($row->fullname);
//            $row->editable = true;
//            return $row;
//        });
//
//        return response()->json([
//            'total' => $total,
//            'rows'  => $rows,
//        ]);
//    }
//
//
//    public function create()
//    {
//        return view('panel.pages.customer-services.inquiry.edit')->with([
//            'companies' => Company::with('parameters')->whereNotIn('id', [1])->select(['id','name'])->get(),
//            'method'    => 'POST',
//            'action'    => route('inquiry.store'),
//            'data'      => null
//        ]);
//    }
//
//    /**
//     * Store a newly created resource in storage.
//     */
//    public function store(InquiryRequest $request): RedirectResponse
//    {
//        $validated = $request->validate($this->rules);
//
//        $userID = auth()->id();
//
//        $data = Inquiry::create( array_merge(
//            $validated,
//            ['user_id' => $userID]
//        ));
//
//        Log::channel('daily')->info("New request created by user %ID:$userID% ".json_encode($data));
//
//        return back()->with(['notify' => ['type' => 'success', 'message' => 'Created successfully']]);
//    }
//
//    /**
//     * Display the specified resource.
//     */
//    public function show($id): void
//    {
//        //
//    }
//
//    /**
//     * Show the form for editing the specified resource.
//     */
//    public function edit(Inquiry $callCenter)
//    {
//        return view('panel.pages.customer-services.inquiry.edit')->with([
//            "companies"  => Company::whereNot('id', 1)->select(['id','name'])->pluck('name','id')->toArray(),
////            "subjects"   => Subjects::get()->toArray(),
////            "sources"    => Sources::get()->toArray(),
////            "statuses"   => Statuses::get()->toArray(),
//            "operators"  => Role::whereIn('key', ['developer', 'call-center-operator'])->first()->users->pluck('name','phone')->toArray(),
//            "callCenter" => $callCenter,
//        ]);
//    }
//
//    /**
//     * Update the specified resource in storage.
//     */
//    public function update(Request $request, Inquiry $callCenter): RedirectResponse
//    {
//        $validated = $request->validate($this->rules);
//
//        $callCenter->update($validated);
//
//        $userID = auth()->id();
//
//        Log::channel('daily')->info("Request update by user %ID:$userID% ".json_encode($callCenter->getChanges()) );
//
//        return back()->with(['notify' => ['type' => 'success', 'message' => 'Updated successfully']]);
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     */
//    public function destroy(Inquiry $callCenter)
//    {
//        $callCenter->delete();
//
//        $userID = auth()->id();
//        Log::channel('daily')->warning("Request delete by user %ID:$userID% ".json_encode($callCenter) );
//
//        return response('ok',200);
//    }
}

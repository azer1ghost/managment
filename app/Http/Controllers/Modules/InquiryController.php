<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\InquiryRequest;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class InquiryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Inquiry::class, 'inquiry');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('panel.pages.customer-services.inquiry.index');
    }

    public function create()
    {
        return view('panel.pages.customer-services.inquiry.edit')
            ->with([
                'method' => 'POST',
                'action' => route('inquiry.store'),
                'data'   => null
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InquiryRequest $request): RedirectResponse
    {
        $validated = $request->validate($this->rules);

        $userID = auth()->id();

        $data = Inquiry::create( array_merge(
            $validated,
            ['user_id' => $userID]
        ));

        //Log::channel('daily')->info("New request created by user %ID:$userID% ".json_encode($data));

        return back()->with(
            notify()->info($data->name)
        );
    }



    public function show(Inquiry $inquiry)
    {
        return view('panel.pages.customer-services.inquiry.edit')
            ->with([
                'method' => null,
                'action' => null,
                'data'   => null
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inquiry $inquiry)
    {
        return view('panel.pages.customer-services.inquiry.edit')
            ->with([
                'method' => null,
                'action' => null,
                'data'   => null
            ]);
    }

    public function restore(Inquiry $inquiry){
        return null;
    }

    public function forceDelete(Inquiry $inquiry){
        return null;
    }




//    public function table(Request $request): JsonResponse
//    {
//        $this->authorize('viewAny-inquiry');
//
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
//        //table row mutator
//        $rows = $data->map(function ($row){
////            $row->subject = Parameter::find($row->id)->name;
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
//    /**
//     * Display the specified resource.
//     */


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

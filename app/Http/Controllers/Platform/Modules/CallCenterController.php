<?php

namespace App\Http\Controllers\Platform\Modules;

use App\Http\Controllers\Controller;
use App\Models\CallCenter;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CallCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('panel.pages.customer-services.call-center.index')->with([
            "companies" => Company::where('id','!=', 4)->select(['id','name'])->pluck('name','id')->toArray(),
            "subjects"  => ['Problem', 'Support'],
            "sources"   => ['Instagram', 'Facebook', 'Whatsapp', 'Call'],
            "statuses"  => ['Done', 'Pending', 'Rejected']
        ]);
    }

    public function table(Request $request): JsonResponse
    {
        $limit  = $request->get('limit')  ?? 25;
        $offset = $request->get('offset') ?? 0;
        $search = $request->get('search');
        $sort   = $request->get('sort')   ?? 'created_at';
        $order  = $request->get('order')  ?? 'asc';

        $query = CallCenter::query();

        $query->latest()->orderBy($sort, $order);

        if ($search) {
            $query
                ->where('note', 'like', "%$search%")
                ->orWhere('fullname', 'like', "%$search%")
                ->orWhere('phone', 'like', "%$search%");
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

        return response()->json([
            'total' => $total,
            'rows'  => $data,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required',
            'time'  => "required",
            'client'  => "required|string|max:255",
            'fullname'  => "required|string|max:255",
            'phone'  => "required|string|max:255",
            'subject'  => "required|string|max:255",
            'source'  => "required|string|max:255",
            'note'  => "required|string|max:255",
            'redirected'  => "required|string|max:255",
            'status' => "required|bool",
            'company_id'  => "required|int|max:11",
        ]);

        CallCenter::create( array_merge(
            $validated,
            ['user_id' => auth()->id()]
        ));

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
    public function edit($id)
    {
        $validated = $request->validate([
            'date' => 'required',
            'time'  => "required",
            'client'  => "required|string|max:255",
            'fullname'  => "required|string|max:255",
            'phone'  => "required|string|max:255",
            'subject'  => "required|string|max:255",
            'source'  => "required|string|max:255",
            'note'  => "required|string|max:255",
            'redirected'  => "required|string|max:255",
            'status' => "required|bool",
            'company_id'  => "required|int|max:11",
        ]);

        CallCenter::create( array_merge(
            $validated,
            ['user_id' => auth()->id()]
        ));

        return back()->with(['notify' => ['type' => 'success', 'message' => 'Created successfully']]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}

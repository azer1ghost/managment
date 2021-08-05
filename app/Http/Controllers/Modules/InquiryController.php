<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\InquiryRequest;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;

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
        $validated = $request->validated();

        $data = auth()->user()->inquiries()->create($validated);

        //Log::channel('daily')->info("New request created by user %ID:$userID% ".json_encode($data));

        return redirect()->route('inquiry.index')->withNotify('info','Inquiry');
    }


    public function show(Inquiry $inquiry)
    {
        return view('panel.pages.customer-services.inquiry.edit')
            ->with([
                'method' => null,
                'action' => null,
                'data'   => $inquiry
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inquiry $inquiry)
    {
        return view('panel.pages.customer-services.inquiry.edit')
            ->with([
                'method' => "PUT",
                'action' => route('inquiry.update', $inquiry),
                'data'   => $inquiry
            ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(InquiryRequest $request, Inquiry $inquiry): RedirectResponse
    {

        /**
         * 1. Get fillable columns array and make it collection
         * 2. flip key and values
         * 3. set all attributes values to null and set user_id to current user ID
         * 4. Merge this attributes with validated inputs
         * 5. Convert result collection to Array
         */
        $setAllColumnsToNullAndMergeWithRequest = collect($inquiry->getFillable())
            ->flip()
            ->map(fn ($name, $key) => ($key === "user_id") ? $request->user()->id: null)
            ->merge($request->validated())
            ->toArray();

        //dd($setAllColumnsToNullAndMergeWithRequest);

        $inquiry->backups()->create($inquiry->replicate()->getAttributes());

        $inquiry->update($setAllColumnsToNullAndMergeWithRequest);

//      Log::channel('daily')->info("Request update by user %ID:".$request->user()->id."% ".json_encode($callCenter->getChanges()) );

        return redirect()->route('inquiry.index')->withNotify('info','Inquiry Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inquiry $inquiry)
    {
        if ($inquiry->delete()) {
            //  Log::channel('daily')->warning("Request delete by user %ID:{".auth()->id()."}% ".json_encode($inquiry) );
            return response('OK', 200);
        }
        return response('',204);
    }

    public function restore(Inquiry $inquiry){
        return null;
    }

    public function forceDelete(Inquiry $inquiry){
        return null;
    }




//
//        if ($search) {
//            $query
//                ->where('note', 'like', "%$search%")
//                ->orWhere('fullname', 'like', "%$search%")
//                ->orWhere('phone', 'like', "%$search%")
//                ->orWhere('user', 'like', "$search%");
//        }



}

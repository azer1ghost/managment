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
        auth()->user()->inquiries()->create($request->validated());

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
     *
     * 1. flip key and values
     * 2. set all attributes values to null and set user_id to current user ID
     * 3. Merge this attributes with validated inputs
     * 4. Convert result collection to Array
     *
     */
    public function update(InquiryRequest $request, Inquiry $inquiry): RedirectResponse
    {
        $backup = $inquiry->replicate()->getAttributes();

        $inquiry->update(
            $inquiry
                ->getColumns()
                ->flip()
                ->map(fn ($name, $key) => ($key === "user_id") ? $request->user()->id: null)
                ->merge($request->validated())
                ->toArray()
        );

        if ($inquiry->getChanges()) {
            $inquiry->backups()->create($backup);
        }

        return redirect()->route('inquiry.index')->withNotify('info','Inquiry Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inquiry $inquiry)
    {
        return $inquiry->delete() ? response('OK', 200) : response('',204);;
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

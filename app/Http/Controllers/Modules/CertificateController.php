<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\CertificateRequest;
use App\Models\Certificate;
use App\Models\Organization;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Certificate::class, 'certificate');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');

        return view('pages.certificates.index')
            ->with([
                'certificates' => Certificate::query()
                    ->when($search, fn ($query) => $query->where('name', 'like', "%$search%"))
                    ->latest()
                    ->simplePaginate(10)
            ]);
    }
    public function create()
    {
        return view('pages.certificates.edit')
            ->with([
                'action' => route('certificates.store'),
                'method' => 'POST',
                'data' => new Certificate(),
                'organizations' => Organization::pluck('name', 'id')->toArray(),
            ]);
    }

    public function store(CertificateRequest $request)
    {
        $validated = $request->validated();

        $certificate = Certificate::create($validated);

        return redirect()
            ->route('certificates.edit', $certificate)
            ->withNotify('success', $certificate->getAttribute('name'));
    }

    public function show(Certificate $certificate)
    {
        return view('pages.certificates.edit')
            ->with([
                'action' => null,
                'method' => new Certificate(),
                'data' => $certificate,
                'organizations' => Organization::pluck('name', 'id')->toArray(),
            ]);
    }

    public function edit(Certificate $certificate)
    {
        return view('pages.certificates.edit')
            ->with([
                'action' => route('certificates.update', $certificate),
                'method' => "PUT",
                'data' => $certificate,
                'organizations' => Organization::pluck('name', 'id')->toArray(),
            ]);
    }

    public function update(CertificateRequest $request, Certificate $certificate)
    {
        $validated = $request->validated();

        $certificate->update($validated);

        return back()->withNotify('info', $certificate->getAttribute('name'));
    }

    public function destroy(Certificate $certificate)
    {
        if ($certificate->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}

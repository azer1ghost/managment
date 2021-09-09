<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\GadgetRequest;
use App\Models\Gadget;
use App\Models\Social;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class GadgetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Gadget::class, 'gadget');
    }

    public function index()
    {

    }

    public function create()
    {

    }

    public function store(GadgetRequest $request)
    {

    }

    public function show(Gadget $gadget)
    {

    }

    public function edit(Gadget $gadget)
    {

    }

    public function update(GadgetRequest $request, Gadget $gadget)
    {

    }

    public function destroy(Gadget $gadget)
    {

    }
}

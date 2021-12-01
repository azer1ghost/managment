<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Advertising;

class AdvertisingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Advertising::class, 'advertising');
    }

    public function index()
    {

    }

    public function create()
    {

    }

    public function store()
    {

    }

    public function show()
    {

    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}

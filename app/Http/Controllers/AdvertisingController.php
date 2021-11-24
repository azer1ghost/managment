<?php

namespace App\Http\Controllers;

use App\Models\Advertising;
use Illuminate\Http\Request;

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

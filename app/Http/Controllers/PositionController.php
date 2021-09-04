<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Position::class, 'position');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');

        return view('panel.pages.positions.index')
            ->with([
                'positions' => Position::with(['role', 'department'])->paginate()
            ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Position $position)
    {
        //
    }

    public function edit(Position $position)
    {
        //
    }

    public function update(Request $request, Position $position)
    {
        //
    }

    public function destroy(Position $position)
    {
        //
    }
}

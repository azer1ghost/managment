<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Http\Request;

class DatabaseNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(DatabaseNotification::class, 'notification');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');

        return view('pages.notifications.index')
            ->with([
                'notifications' => DatabaseNotification::query()
                    ->when($search, fn ($query) => $query
                        ->where('data->phone',   'LIKE', "%$search%")
                        ->orWhere('data->email', 'LIKE', "%$search%"))
                    ->latest()
                    ->simplePaginate(10)
            ]);
    }
}

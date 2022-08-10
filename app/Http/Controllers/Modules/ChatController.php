<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    public function index()
    {
        return view('pages.chats.index');
    }
}

<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function index()
    {
        return view('make-template');
    }

    public function store(Request $request)
    {
        EmailTemplate::create([
            'name' => $request->get('name'),
            'content' => $request->get('content'),
        ]);
        return back();
    }

    public function edit(EmailTemplate $template)
    {
        return view('edit-template', $template);
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
//        dd($request->all());
        $emailTemplate->update([
            'content' => $request->get('content'),
        ]);
        return back();
    }
}

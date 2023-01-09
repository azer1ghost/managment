<?php

namespace App\Http\Controllers\Modules;

use App\Http\Requests\JobInstructionRequest;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\JobInstruction;
use App\Models\User;
use http\Client\Request;

class JobInstructionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(JobInstruction::class, 'job_instruction');
    }

    public function index()
    {
        $jobInstructions = JobInstruction::where('user_id',auth()->id())->first();
        return view('pages.job-instructions.index')
            ->with(['jobInstructions' => $jobInstructions
            ]);
    }

    public function create()
    {
        return view('pages.job-instructions.edit')->with([
            'action' => route('job-instructions.store'),
            'method' => 'POST',
            'data' => new JobInstruction(),
            'users' => User::isActive()->get(['id', 'name', 'surname']),
            'departments' => Department::get(['id', 'name'])
        ]);
    }

    public function store(JobInstructionRequest $request)
    {
        $jobInstruction = JobInstruction::create($request->validated());

        return redirect()
            ->route('job-instructions.edit', $jobInstruction)
            ->withNotify('success', optional($jobInstruction->getRelationValue('users'))->getAttribute('name'));
    }

    public function show(JobInstruction $jobInstruction)
    {
        return view('pages.job-instructions.edit')->with([
            'action' => null,
            'method' => null,
            'data' =>$jobInstruction,
            'users' => User::isActive()->get(['id', 'name', 'surname']),
            'departments' => Department::get(['id', 'name'])
        ]);
    }

    public function edit(JobInstruction $jobInstruction)
    {
        return view('pages.job-instructions.edit')->with([
            'action' => route('job-instructions.update', $jobInstruction),
            'method' => 'PUT',
            'data' => $jobInstruction,
            'users' => User::isActive()->get(['id', 'name', 'surname']),
            'departments' => Department::get(['id', 'name'])
        ]);
    }

    public function update(JobInstructionRequest $request, JobInstruction $jobInstruction)
    {
        $jobInstruction->update($request->validated());

        return redirect()
            ->route('job-instructions.edit', $jobInstruction)
            ->withNotify('success', $jobInstruction->getRelationValue('users')->getAttribute('name'));
    }

    public function destroy(JobInstruction $jobInstruction)
    {
        if ($jobInstruction->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

    public function getInstruction($id)
    {
        $jobInstructions = JobInstruction::where('user_id',$id)->first();
        return view('pages.job-instructions.index')
            ->with(['jobInstructions' => $jobInstructions
            ]);
    }
}

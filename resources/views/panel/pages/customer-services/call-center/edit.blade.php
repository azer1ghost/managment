@extends('layouts.main')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            <x-sidebar></x-sidebar>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-muted mt-2">Edit Request</h4>
                </div>
                <div class="card-body">
                    <form action="{{route('call-center.update', $callCenter)}}" id="createForm" method="POST" >
                        @csrf
                        @method('PUT')
                        <div class="tab-content form-row mt-4" >
                            <x-input::select name="company_id" label="Company" :value="$callCenter->company_id" :options="$companies" width="3" class="pr-3" />
                            <x-input::text name="date" :value="$callCenter->date" type="date" width="3" class="pr-2" />
                            <x-input::text name="time" :value="$callCenter->time" type="time" width="3" class="pr-2" />
                            <x-input::select name="subject" :value="$callCenter->subject" :options="$subjects" width="3" class="pr-3" />
                            <x-input::select name="source" :value="$callCenter->source" :options="$sources" width="3" class="pr-3" />
                            <x-input::text name="phone" width="3" :value="$callCenter->phone" class="pr-2" />
                            <x-input::text name="client" width="3" :value="$callCenter->client" placeholder="MBX or profile" class="pr-2" />
                            <x-input::text name="fullname" :value="$callCenter->fullname" width="3" class="pr-2" />
                            <x-input::select name="status" :value="$callCenter->status" :options="$statuses" width="3" class="pr-3" />
                            <x-input::select name="redirected" :options="$operators" label="Redirect" width="4" class="pr-2" />
                            <x-input::textarea name="note" :value="$callCenter->note"/>
                        </div>
                        <a href="{{route('call-center.index')}}" class="btn btn-outline-danger">Back</a>
                        <button class="btn btn-outline-primary float-right">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

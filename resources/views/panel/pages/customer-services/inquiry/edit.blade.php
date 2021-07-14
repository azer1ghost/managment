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
                    <div wire:offline>
                        You are now offline.
                    </div>
                </div>
                <div id="app" class="card-body">
                    <form action="{{$action}}" id="createForm" method="POST" >
                        @csrf
                        @method($method)
                        <div class="tab-content form-row mt-4 mb-5" >

                            <x-input::text name="date" value="{{optional($data)->date ?? now()->format('Y-m-d')}}" type="date" width="3" class="pr-2" />
                            <x-input::text name="time" value="{{optional($data)->time ?? now()->format('H:i')}}" type="time" width="3" class="pr-2" />


                            <div class="form-group col-6">
                                <label for="exampleFormControlSelect1">Select Company</label>
                                <select class="form-control" id="exampleFormControlSelect1">
                                    @foreach(App\Models\Company::whereNotIn('id', [1])->get() as $key => $company)
                                        <option value="{{$company->key}}">{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>



{{--                            <div class="form-group col-12 col-md-4">--}}
{{--                                <label for="subjectInput">Subject</label>--}}
{{--                                <select name="subject" id="subjectInput" class="form-control">--}}
{{--                                    @foreach($subjects as $key => $subject)--}}
{{--                                        <option value="{{$key}}">{{ucfirst($subject->text)}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}

{{--                            <x-input::select name="company_id" label="Company" :value="$callCenter->company_id" :options="$companies" width="3" class="pr-3" />--}}
{{--                            <x-input::text name="date" :value="$callCenter->date" type="date" width="3" class="pr-2" />--}}
{{--                            <x-input::text name="time" :value="$callCenter->time" type="time" width="3" class="pr-2" />--}}
{{--                            <x-input::select name="source" :value="$callCenter->source" :options="$sources" width="3" class="pr-3" />--}}

{{--                            <x-input::select name="subject" :value="$callCenter->subject" :options="$subjects" width="3" class="pr-3" />--}}
{{--                            <x-input::select name="kind" :options="$kinds" :value="$callCenter->kind" width="3" class="pr-3" />--}}

{{--                            <input type="hidden" id="resetKind">--}}
{{--                            <div class="form-group col-12 col-md-4">--}}
{{--                                <label for="subjectInput">Subject</label>--}}
{{--                                <select name="subject" id="subjectInput" class="form-control">--}}
{{--                                    @foreach($subjects as $key => $subject)--}}
{{--                                        <option @if($callCenter->subject === $key) selected @endif value="{{$key}}">{{ucfirst($subject->text)}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}

{{--                            @foreach($subjects as $key => $subject)--}}
{{--                                @if($subject->kinds)--}}
{{--                                    <div id="kind-{{$key}}" class="form-group col-12 col-md-4 kinds">--}}
{{--                                        <label for="subjectInput-{{$key}}">Kind kind</label>--}}
{{--                                        <select name="kind" id="subjectInput-{{$key}}" class="form-control">--}}
{{--                                                <option disabled selected value="null">Select</option>--}}
{{--                                            @foreach($subject->kinds as $key => $kind)--}}
{{--                                                <option @if($callCenter->kind === $key) selected @endif value="{{$key}}">{{ucfirst($kind)}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                            @endforeach--}}


{{--                            <x-input::text name="phone" width="3" :value="$callCenter->phone" class="pr-2" />--}}
{{--                            <x-input::text name="client" width="3" :value="$callCenter->client" placeholder="MBX or profile" class="pr-2" />--}}
{{--                            <x-input::text name="fullname" :value="$callCenter->fullname" width="3" class="pr-2" />--}}
{{--                            <x-input::select name="status" :value="$callCenter->status" :options="$statuses" width="3" class="pr-3" />--}}
{{--                            <x-input::select name="redirected" :options="$operators" label="Redirect" width="4" class="pr-2" />--}}
{{--                            <x-input::textarea name="note" :value="$callCenter->note"/>--}}

                        </div>
                        <a href="{{route('inquiry.index')}}" class="btn btn-outline-danger">Back</a>
                        <button class="btn btn-outline-primary float-right">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>

{{--    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>--}}

    <script>
        let app = new Vue({
            el: '#app',
            data: {
                message: 'Hello Vue!'
            }
        })
    </script>
@endsection
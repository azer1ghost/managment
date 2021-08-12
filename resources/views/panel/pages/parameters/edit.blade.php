@extends('layouts.main')
@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
@endsection
@section('content')
<div class="container" xmlns:x-input="http://www.w3.org/1999/html">
    <div class="row justify-content-center">
        <div class="col-md-2">
            <x-sidebar></x-sidebar>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <a href="{{route('parameters.index')}}" class="btn btn-sm btn-outline-primary mr-4">
                        <i class="fa fa-arrow-left"></i>
                        Back
                    </a>
                    Parameters
                </div>
                <div class="card-body">
                    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
                        @method($method) @csrf
                        <input type="hidden" name="id" value="{{optional($data)->id}}">
                        <div class="tab-content form-row mt-4" >
                            <x-input::select  name="parameter_id"  :value="optional($data)->parameter_id"  width="4" class="pr-3" :options="$parameters" />
                            <x-input::select  name="type"  :value="optional($data)->type"   width="4" class="pr-3" :options="$types" />
                            <x-input::text    name="name"  :value="optional($data)->name"   width="4" class="pr-3" />
                            <select name="companies[]" id="companyFilter" multiple class="filterSelector" data-width="fit"  title="Noting selected" >
                                @foreach($companies as $company)
                                    <option @if(optional(optional($data)->companies())->exists() && $data->companies->pluck('id')->contains($company->id)) selected  @endif value="{{$company->id}}">{{ucfirst($company->name)}}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($action)
                            <x-input::submit />
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
<script>$('.filterSelector').selectpicker()</script>

@if(is_null($action))
<script>
    $('input').attr('readonly', true)
    $('select').attr('disabled', true)
    $('textarea').attr('readonly', true)
</script>
@endif
@endsection
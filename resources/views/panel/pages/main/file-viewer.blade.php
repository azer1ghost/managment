@extends('layouts.main')
@section('title', $document->name)

@section('style')
    <style>
        .custom-wrapper main {
            width: 100% !important;
            margin-left: 0 !important;
        }
    </style>
@endsection

@section('content')
    <div class="row m-0" style="position: relative">
        <div>
            @php($images = ['image/jpeg', 'image/jpg', 'image/png'])
            @if(in_array($document->type, $images))
                <img src="{{route('document.temporaryUrl', $document)}}" alt="img" style="width: 500px">
            @else
                <iframe
                        src="https://view.officeapps.live.com/op/embed.aspx?src={{route('document.temporaryUrl', $document)}}"
                        style="width:100%;height:600px;border: 0"
                >
                    <p>Your browser does not support iframes.</p>
                </iframe>
            @endif
        </div>
        <a href="{{route('document.temporaryUrl', $document)}}" download class="btn btn-outline-primary mt-3"><i class="fa fa-download"></i> Download</a>
    </div>
@endsection
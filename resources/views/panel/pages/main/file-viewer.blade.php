@extends('layouts.main')
@section('title', $document->name)
@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link is-current="1">
            Dashboard
        </x-bread-crumb-link>
    </x-bread-crumb>
    <div class="row m-0" style="position: relative">
        @php($images = ['image/jpeg', 'image/jpg', 'image/png'])
        @if(in_array($document->type, $images))
            <img src="{{route('documents.show', $document)}}" alt="img" style="width: 500px">
        @else
            <iframe
                    src="https://view.officeapps.live.com/op/embed.aspx?src={{route('document.temporaryUrl', $document)}}"
                    style="width:100%;height:600px;border: 0"
            >
                <p>Your browser does not support iframes.</p>
            </iframe>
            <script>
            </script>
        @endif
    </div>
@endsection
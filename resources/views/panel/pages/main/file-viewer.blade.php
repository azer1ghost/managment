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
            <div id="spinner-container" class="d-flex justify-content-center" style="width: 100%; height: 100%;position: absolute;top: 0;right: 0">
                <div class="spinner-border text-primary" role="status"></div>
            </div>
            {{--        <iframe--}}
            {{--                src="https://view.officeapps.live.com/op/embed.aspx?src=https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf"--}}
            {{--                style="width:100%;height:600px;border: 0"--}}
            {{--        >--}}
            {{--        </iframe>--}}
            <iframe id="file-viewer" style="width: 100%; height: 550px;border: 0">
                <p>Your browser does not support iframes.</p>
            </iframe>
            <script>
                const iframe = document.getElementById('file-viewer');
                const fileUrl = "{{route('documents.show', $document)}}";
                let loaded = false;

                iframe.addEventListener("load", function() {
                    loaded = true;
                    $('#spinner-container').removeClass('d-flex').addClass('d-none');
                });

                // Check if loading is complete
                const _interval = setInterval(function (){
                    if (loaded){
                        clearInterval(_interval);
                        return;
                    }
                    iframe.src = `https://docs.google.com/viewer?url=${fileUrl}&embedded=true`;
                }, 1000);
            </script>
        @endif
    </div>
@endsection
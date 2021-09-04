@extends('layouts.main')

@section('title', __('translates.navbar.signature'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Your email signature for <b>{{$company->name}} </b></div>
                    <div class="card-body row">
                        <section class="border border-info" id="signature_rendered">
                            @include('panel.pages.signature.template.template1', ['company'=> $company, 'user' => auth()->user()])
                        </section>
                        <div>
                            <button type="button" onclick="copy()" class="btn btn-outline-primary">
                                <i class="fal fa-copy"></i> Copy
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function copy() {
            /* Get the text field */
            let urlField = document.getElementById("signature_rendered")
            let range = document.createRange()
            range.selectNode(urlField)
            window.getSelection().addRange(range)
            document.execCommand('copy')
        }
    </script>
@endsection
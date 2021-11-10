@extends('layouts.main')

@section('title', __('translates.navbar.signature'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('signature-select-company')">
            @lang('translates.navbar.signature')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.general.signature_for') {{optional($company)->getAttribute('name')}}
        </x-bread-crumb-link>
    </x-bread-crumb>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="row">
                    <section class="border border-info mb-3" id="signature_rendered">
                        @include('panel.pages.signature.template.template1', ['company'=> $company, 'user' => auth()->user()])
                    </section>
                    <div>
                        <button type="button" onclick="copy()" class="btn btn-outline-primary">
                            <i class="fal fa-copy"></i> @lang('translates.buttons.copy')
                        </button>
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
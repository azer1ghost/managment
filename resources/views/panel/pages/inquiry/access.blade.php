@extends('layouts.main')

@section('title', __('translates.navbar.inquiry'))
@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('inquiry.index')">
            @lang('translates.navbar.inquiry')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('inquiry.show', $inquiry)">
            {{ $inquiry->getAttribute('code')}}
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.access')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form method="POST">
        @csrf
        @livewire('inquiry-access-creator' , ['inquiry' => $inquiry])
    </form>
@endsection
@section('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>

        $(function() {
            dispatchDatePicker()
        });

        window.addEventListener('user-added', event => {
            $(function() {
                dispatchDatePicker();
            });
        })

        function dispatchDatePicker() {
            $('.editable-ended-at').daterangepicker({
                    opens: 'left',
                    locale: {
                        format: "YYYY-MM-DD HH:mm:ss",
                    },
                    singleDatePicker: true,
                    timePicker: true,
                    timePicker24Hour: true,
                }, function(start, end, label) {}
            );
        }
    </script>
@endsection


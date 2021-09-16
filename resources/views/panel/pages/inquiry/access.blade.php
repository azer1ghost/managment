@extends('layouts.main')

@section('title', __('translates.navbar.inquiry'))
@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{route('inquiry.index')}}" class="btn btn-sm btn-outline-primary mr-4">
                <i class="fa fa-arrow-left"></i>
                @lang('translates.buttons.back')
            </a>
        </div>
        <div class="card-body">
            <form method="POST">
                @csrf
                @livewire('inquiry-access-creator' , ['inquiry' => $inquiry])
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        $(function() {
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
        });
    </script>
    <script>aa
        window.addEventListener('user-added', event => {
            $(function() {
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
            });
        })
    </script>
@endsection


@extends('layouts.main')

@section('title', trans('translates.navbar.work'))
@section('style')
    <style>
        @page {
            size: 21cm 29.7cm;
            margin: 8mm 5mm 10mm 5mm;
            height: 95%;
            width: 95%;
            page-break-after: avoid !important;
            page-break-before: avoid !important;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            #reportPDF, #reportPDF * {
                visibility: visible;
            }
        }
    </style>
@endsection
@section('content')
    <div class="row d-flex justify-content-center" id="reportPDF">
        <div class="col-md-10">
            <div class="card p-3 py-4 shadow-sm">
                <div class="text-center mt-3">
                    <h4 class="my-2">@lang('translates.users.titles.employee')</h4>

                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">@lang('translates.columns.full_name')</th>
                            <th scope="col">@lang('translates.columns.department')</th>
                            <th scope="col">@lang('translates.fields.position')</th>
                            <th scope="col">@lang('translates.general.select_date')</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{$user->getFullnameAttribute()}}</td>
                            <td>{{$user->getRelationValue('compartment')->getAttribute('name')}}</td>
                            <td>{{$user->getRelationValue('position')->getAttribute('name')}}</td>
                            <td class="font-weight-bolder">{{request()->get('created_at')}}</td>
                        </tr>
                        </tbody>
                    </table>

                    <h4 class="my-2">@lang('translates.columns.user_works')</h4>

                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">@lang('translates.navbar.services')</th>
                            <th scope="col">@lang('translates.columns.total')</th>
                            <th scope="col">@lang('translates.columns.verified')</th>
                            <th scope="col">@lang('translates.columns.rejected')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($services as $service)
                            <tr>
                                <th scope="row">{{$loop->iteration}}</th>
                                <td>{{$service->getAttribute('name')}}</td>
                                <td class="font-weight-bold">{{$service->works_count}}</td>
                                <td>{{$service->works_verified}}</td>
                                <td>{{$service->works_rejected}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function (event) {
            window.print()
            window.addEventListener("afterprint", function (event) {
                window.close();
            });
        });
    </script>
@endsection
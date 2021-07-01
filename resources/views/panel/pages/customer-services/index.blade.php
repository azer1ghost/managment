@extends('layouts.main')


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            <x-sidebar></x-sidebar>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Call Center</div>
                <div class="card-body">
                    <table id="table"
                           data-buttons-class="secondary mr-1"
                           data-toolbar=".toolbar"
                           data-toolbar-align="right"
                           data-show-refresh="true"
                           data-search="true"
                           data-toggle="table"
                           data-url="{{ route('call-center.table')  }}"
                           data-side-pagination="server"
                           data-pagination="true"
                           data-page-list="[10, 25, 50, 100]"
                           data-buttons="buttons"
                           class="table">
                        <thead>
                        <th scope="col" data-checkbox="true"></th>
                        <th scope="col" data-sortable="true" data-field="id" >ID</th>
                        <th scope="col" data-sortable="true" data-field="key">Item Key</th>
                        <th scope="col" data-sortable="true" data-field="name" >Item Name</th>
                        <th scope="col" data-formatter="operateFormatter" data-field="operate" class="text-center">Actions</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script>
        let $table = $('#table')

    </script>
@endsection
@extends('layouts.main')


@section('content')

<!-- Modal -->
<div class="modal fade " id="createModal" tabindex="-1" role="dialog"
     aria-hidden="true" xmlns:x-input="http://www.w3.org/1999/html">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Call center requests</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('call-center.store')}}" id="createForm" method="POST" >
                    @csrf
                    <div class="tab-content form-row mt-4" >
                        <x-input::select name="company_id" label="Company" :options="$companies" width="3" class="pr-3" />
                        <x-input::text name="date" value="{{now()->timezone('Asia/Baku')->format('Y-m-d')}}" type="date" width="3" class="pr-2" />
                        <x-input::text name="time" value="{{now()->timezone('Asia/Baku')->format('H:i')}}" type="time" width="3" class="pr-2" />
                        <x-input::select name="subject" :options="$subjects" width="3" class="pr-3" />
                        <x-input::select name="source" :options="$sources" width="3" class="pr-3" />
                        <x-input::text name="phone" width="3" value="+994 " class="pr-2" />
                        <x-input::text name="client" width="3" placeholder="MBX or profile" class="pr-2" />
                        <x-input::text name="fullname" width="3" class="pr-2" />
                        <x-input::select name="status" :options="$statuses" width="3" class="pr-3" />
                        <x-input::text name="redirected" width="9" class="pr-2" />
                        <x-input::text name="note"/>
{{--                        <x-input::text name="mail" label="Company email" width="4" class="pr-3" />--}}
{{--                        <x-input::text name="phone"  label="Company phone" width="4" class="pr-3" />--}}
{{--                        <x-input::text name="mobile" label="Company mobile" width="4" class="pr-3" />--}}
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" form="createForm" class="btn btn-primary">Create request</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog"
     aria-hidden="true" xmlns:x-input="http://www.w3.org/1999/html">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Call center requests</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="viewBody">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>





<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            <x-sidebar></x-sidebar>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Call Center</div>
                <div class="toolbar">
                    <button data-toggle="modal" data-target="#createModal" class="btn btn-success">
                        <i class="fal fa-plus"></i>
                    </button>
                </div>
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
                            <th scope="col" data-sortable="true" data-field="id" >#ID</th>
                            <th scope="col" data-sortable="true" data-field="date" >Date</th>
                            <th scope="col" data-sortable="true" data-field="fullname">Fullname</th>
                            <th scope="col" data-sortable="true" data-field="subject" >Subject</th>
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

    {{--function destroy(){--}}
    {{--    if (confirm("Want to delete?")) {--}}
    {{--        $.ajax('{{route('call-center.destroy','1')}}', {--}}
    {{--            method: 'delete',--}}
    {{--            data: {--}}
    {{--                _token: '{{csrf_token()}}',--}}
    {{--                data: JSON.stringify($table.bootstrapTable('getSelections'))--}}
    {{--            },--}}
    {{--            success: function (data,status,xhr) {   // success callback function--}}
    {{--                $table.bootstrapTable('refresh')--}}
    {{--            },--}}
    {{--            error: function (jqXhr, textStatus, errorMessage) { // error callback--}}
    {{--                alert('error')--}}
    {{--            }--}}
    {{--        });--}}
    {{--    }--}}
    {{--}--}}

    function operateFormatter(value, row, index) {
        return [
            '<button onclick="view(' + row + ')" class="btn btn-sm btn-outline-info mr-1"><i class="fal fa-eye"></i></button>',
            '<button onclick="edit(' + row.id + ')" class="btn btn-sm btn-outline-success mr-1"><i class="fal fa-pen"></i></button>',
            '<button onclick="remove(' + row.id + ')" class="btn btn-sm btn-outline-danger  ml-1"><i class="fal fa-trash"></i></button>'
        ].join('')
    }

    function view(data) {
        $('#viewModal').modal('show')
        $.each( data, function( key, value ) {
            $('#viewBody').append( key + ": " + value );
        });
    }

    function edit(dataID){
        window.location.href = "{{route('call-center.edit', '%id%')}}".replace('%id%', dataID);
    }

    function remove(dataID) {

    }


    @if($errors->any())
        $('#createModal').modal('show')
    @endif

    window.addEventListener('keypress', function (e) {
        if (e.key === '+') {
            $('#createModal').modal('show')
        }
    }, false);
</script>
@endsection
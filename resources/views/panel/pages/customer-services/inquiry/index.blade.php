@extends('layouts.main')

@section('style')
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <!-- (Optional) Latest compiled and minified JavaScript translation files -->
    <script src="https://cdn.jsdelivr.net/npm/tableexport.jquery.plugin@1.10.21/tableExport.min.js"></script>
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            <x-sidebar></x-sidebar>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Call Center</div>
                <div class="toolbar">
                    <select id="subjectFilter" multiple class="filterSelector" data-width="fit" title="Noting selected" >
{{--                        @foreach($subjects as $key => $subject)--}}
{{--                            <option value="{{$key}}">{{ucfirst($subject->text)}}</option>--}}
{{--                        @endforeach--}}
                    </select>
{{--                    <button class="btn btn-outline-secondary">--}}
{{--                        <i class="fal fa-calendar"></i>--}}
{{--                    </button>--}}
                    <input id="dateFilter" class="btn" type="date">
{{--                    <select class="btn" style="width: fit-content" id="locale">--}}
{{--                        @foreach(config('app.locales') as $key => $locale)--}}
{{--                            <option @if(app()->getLocale() === $key) selected @endif value="{{$key}}">{{ucfirst($locale)}}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
                    <a href="{{route('inquiry.create')}}" class="btn btn-success">
                        <i class="fal fa-plus"></i>
                    </a>
                </div>
                <div class="card-body">
                    <table id="table"
                           data-buttons-class="secondary mr-1"
                           data-toolbar=".toolbar"
                           data-toolbar-align="right"
                           data-show-refresh="true"
                           data-search="true"
                           data-toggle="table"
                           data-url="{{ route('inquiry.table')  }}"
                           data-method="post"
                           data-side-pagination="server"
                           data-pagination="true"
                           data-page-list="[10, 25, 50, 100]"
                           data-buttons="buttons"
                           data-detail-view="true"
                           data-detail-formatter="detailFormatter"
                           data-detail-view-icon="false"
                           data-query-params="filtering"
                           class="table">
                        <thead>
                            <th scope="col" data-sortable="true" data-field="id" >#ID</th>
                            <th scope="col" data-sortable="true" data-field="date" >Date</th>
                            <th scope="col" data-sortable="true" data-field="fullname">Fullname</th>
                            <th scope="col" data-sortable="true" data-field="subject" >Subject</th>
                            <th scope="col" data-sortable="true" data-field="status" >Status</th>
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

    $(function() {
        $('#sortable').change(function () {
            $table.bootstrapTable('refreshOptions', {
                sortable: $('#sortable').prop('checked')
            })
        })
    })

    function operateFormatter(value, row, index) {
        return [
            '<button class="btn btn-sm btn-outline-info mr-1 detail-icon"><i class="fal fa-eye"></i></button>',
            '<button onclick="edit(' + row.id + ')" class="btn btn-sm btn-outline-success mr-1"><i class="fal fa-pen"></i></button>',
            '<button onclick="remove(' + row.id + ')" class="btn btn-sm btn-outline-danger mr-1"><i class="fal fa-trash"></i></button>'
        ].join('')
    }

    function detailFormatter(index, row) {
        let html = []
        $.each(row, function (key, value) {
            html.push('<p><b>' + key + ':</b> ' + value + '</p>')
        })
        return html.join('')
    }

    function edit(dataID){
        window.location.href = "{{route('inquiry.edit', '%id%')}}".replace('%id%', dataID);
    }

    function remove(dataID){
        if (confirm("Want to delete?")) {
            $.ajax('{{route('inquiry.destroy','%id%')}}'.replace('%id%', dataID), {
                method: 'delete',
                data: {
                    _token: '{{csrf_token()}}'
                },
                success: function (data,status,xhr) {   // success callback function
                    $.notify("Deleted Success!", "warn", { style: 'bootstrap' });
                    $table.bootstrapTable('refresh')
                },
                error: function (jqXhr, textStatus, errorMessage) { // error callback
                    alert('error')
                }
            });
        }
    }

    $('.filterSelector').selectpicker();

    function filtering(params) {
        params.subject = $('#subjectFilter').val()
        params.date    = $('#dateFilter').val()
        params._token = "{{csrf_token()}}"
        return params;
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


{{--function destroy(){--}}
{{--    if (confirm("Want to delete?")) {--}}
{{--        $.ajax('{{route('inquiry.destroy','1')}}', {--}}
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

{{--$('.kinds').hide().find('select').removeAttr('name')--}}

{{--$('#subjectInput').change(function () {--}}
{{--$('.kinds').hide().find('select').removeAttr('name')--}}
{{--$('#kind-' + $(this).val()).show().find('select').attr('name', 'kind')--}}
{{--})--}}

{{--$('.send-whatsapp-message').click(function (){--}}
{{--let number = $( "select[name='redirected']" ).val()--}}
{{--let note = $( "textarea[name='note']" ).val()--}}

{{--let message = encodeURIComponent(note)--}}

{{--let request = "https://wa.me/" + number + "/?text=" + message--}}

{{--window.open(request, '_blank');--}}
{{--})--}}
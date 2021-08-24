@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
<div class="component row justify-content-between">

    <div class="form-group col-12 col-md-3 mb-3 mb-md-0" >
        <label for="daterange">{{__('translates.filters.date')}}</label>
        <input type="text" placeholder="{{__('translates.placeholders.range')}}" id="daterange" name="daterange" wire:model="daterange" class="form-control">
    </div>

    <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
        <label for="codeFilter">{{__('translates.filters.code')}}</label>
        <input type="search" id="codeFilter" placeholder="{{__('translates.placeholders.code')}}" class="form-control" wire:model="filters.code">
    </div>

    <div class="form-group col-12 col-md-5 mb-3 mb-md-0" wire:ignore>
        <label class="d-block" for="subjectFilter">{{__('translates.filters.subject')}}</label>
        <select id="subjectFilter" multiple class="filterSelector form-control" data-width="fit" wire:model="parameterFilters.subjects" title="{{__('translates.filters.select')}}" >
            @foreach($subjects as $subject)
                <option value="{{$subject->id}}">{{ucfirst($subject->text)}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-12 col-md-1 mb-3 mb-md-0">
        <label for="">{{__('translates.filters.clear')}}</label>
        <a class="form-control btn-outline-danger text-center" href="{{route('inquiry.index')}}">
            <i class="fal fa-times-circle"></i>
        </a>
    </div>

    <div class="col-12 m-2 p-0"></div>

    <div class="form-group col-12 col-md-5 mb-3 mb-md-0"  wire:ignore>
        <label class="d-block" for="companyFilter">{{__('translates.filters.company')}}</label>
        <select id="companyFilter" multiple class="filterSelector" data-width="fit" wire:model="filters.company_id" title="{{__('translates.filters.select')}}" >
            @foreach($companies as $company)
                <option value="{{$company->id}}">{{ucfirst($company->name)}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <hr>
        <div class="float-right">
            @can('create', \App\Models\Inquiry::class)
                <a href="{{route('inquiry.create')}}" class="btn btn-outline-success">
                    <i class="fal fa-plus"></i>
                </a>
            @endcan
            <a href="{{route('inquiry.index', ['trash-box' => true])}}" class="btn btn-outline-secondary">
                <i class="far fa-recycle"></i>
            </a>
        </div>
    </div>

    <div class="col-md-12 mt-3 overflow-auto">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{{__('translates.fields.mgCode')}}</th>
                    <th>{{__('translates.fields.date')}}</th>
                    <th>{{__('translates.fields.time')}}</th>
                    <th>{{__('translates.fields.company')}}</th>
                    <th>{{__('translates.fields.clientName')}}</th>
                    <th>{{__('translates.fields.writtenBy')}}</th>
                    <th>{{__('translates.fields.subject')}}</th>
                    <th>{{__('translates.fields.actions')}}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($inquiries as $inquiry)
                <tr>
                    <td>{{$inquiry->getAttribute('code')}}</td>
                    <td>{{$inquiry->getAttribute('datetime')->format('d-m-Y')}}</td>
                    <td>{{$inquiry->getAttribute('datetime')->format('H:i')}}</td>
                    <td>{{$inquiry->getRelationValue('company')->getAttribute('name')}}</td>
                    <td>{{optional($inquiry->getParameter('fullname'))->getAttribute('value')}}</td>
                    <td>{{$inquiry->getRelationValue('user')->getAttribute('fullname')}}</td>
                    <td>{{optional($inquiry->getParameter('subject'))->getAttribute('text')}}</td>
                    <td>
                        <div class="btn-sm-group" >
                            @if($trashBox)
                                @can('restore', $inquiry)
                                    <a href="{{route('inquiry.restore', $inquiry)}}" class="btn btn-sm btn-outline-primary" >
                                        <i class="fal fa-repeat"></i>
                                    </a>
                                @endcan
                                @can('forceDelete', $inquiry)
                                    <a onclick="deleteAction('{{route('inquiry.forceDelete', $inquiry)}}', '{{$inquiry->code}}')" class="btn btn-sm btn-outline-danger" >
                                        <i class="fa fa-times"></i>
                                    </a>
                                @endcan
                            @else
                                @can('view', $inquiry)
                                    <a href="{{route('inquiry.show', $inquiry)}}" class="btn btn-sm btn-outline-primary">
                                        <i class="fal fa-eye"></i>
                                    </a>
                                @endcan
                                @can('update', $inquiry)
                                    <a href="{{route('inquiry.edit', $inquiry)}}" class="btn btn-sm btn-outline-success">
                                        <i class="fal fa-pen"></i>
                                    </a>
                                @endcan
                                @can('delete', $inquiry)
                                    <a onclick="deleteAction('{{route('inquiry.destroy', $inquiry)}}', '{{$inquiry->code}}')" class="btn btn-sm btn-outline-danger" >
                                        <i class="fal fa-trash-alt"></i>
                                    </a>
                                @endcan
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="col-12">
        <div class="float-right">
            {{ $inquiries->links() }}
        </div>
    </div>
</div>


@section('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script>
        $('.filterSelector').selectpicker()
        $(function() {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left',
                locale: {
                    format: "DD/MM/YYYY",
                }
            }, function(start, end, label) {

            });
        });
        $('#daterange').on('change', function (e) {
            @this.set('daterange', e.target.value)
        });

        function deleteAction(url, name){
            $.confirm({
                title: 'Confirm delete action',
                content: `Are you sure delete <b>${name}</b> ?`,
                autoClose: 'confirm|8000',
                icon: 'fa fa-question',
                type: 'red',
                theme: 'modern',
                typeAnimated: true,
                buttons: {
                    confirm: function () {
                        $.ajax({
                            url:   url,
                            type: 'DELETE',
                            success: function (responseObject, textStatus, xhr)
                            {
                                $.confirm({
                                    title: 'Delete successful',
                                    icon: 'fa fa-check',
                                    content: '<b>:name</b>'.replace(':name',  name),
                                    type: 'blue',
                                    typeAnimated: true,
                                    autoClose: 'reload|3000',
                                    theme: 'modern',
                                    buttons: {
                                        reload: {
                                            text: 'Ok',
                                            btnClass: 'btn-blue',
                                            keys: ['enter'],
                                            action: function(){
                                                window.location.reload()
                                            }
                                        }
                                    }
                                });
                            },
                            error: function ()
                            {
                                $.confirm({
                                    title: 'Confirm!',
                                    content: 'Ops something went wrong! Please reload page and try again.',
                                    type: 'red',
                                    typeAnimated: true,
                                    buttons: {
                                        cancel: function () {

                                        },
                                        reload: {
                                            text: 'Reload page',
                                            btnClass: 'btn-blue',
                                            keys: ['enter'],
                                            action: function(){
                                                window.location.reload()
                                            }
                                        }
                                    }
                                });
                            }
                        });
                    },
                    cancel: function () {

                    },
                }
            });
        }
    </script>
@endsection

{{--    function edit(dataID){--}}
{{--        window.location.href = "{{route('inquiry.edit', '%id%')}}".replace('%id%', dataID);--}}
{{--    }--}}

{{--    @if($errors->any())--}}
{{--        $('#createModal').modal('show')--}}
{{--    @endif--}}

{{--    window.addEventListener('keypress', function (e) {--}}
{{--        if (e.key === '+') {--}}
{{--            $('#createModal').modal('show')--}}
{{--        }--}}
{{--    }, false);--}}




@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
<div class="component row justify-content-between">

    <div class="form-group col-12 col-md-3 mb-3 mb-md-0" >
        <label for="daterange">Filter by Date</label>
        <input type="text" placeholder="Range" id="daterange" name="daterange" wire:model="daterange" class="form-control">
    </div>

    <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
        <label for="codeFilter">Filter by code</label>
        <input type="search" id="codeFilter" placeholder="Enter code" class="form-control" wire:model="filters.code">
    </div>

    <div class="form-group col-12 col-md-5 mb-3 mb-md-0" wire:ignore>
        <label class="d-block" for="subjectFilter">Filter by subject</label>
        <select id="subjectFilter" multiple class="filterSelector form-control" data-width="fit" wire:model="filters.subjects" title="Noting selected" >
            @foreach($subjects as $subject)
                <option value="{{$subject->id}}">{{ucfirst($subject->name)}}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-12 col-md-1 mb-3 mb-md-0">
        <label for="">Clear</label>
        <a class="form-control btn-outline-danger text-center" href="{{route('inquiry.index')}}">
            <i class="fal fa-times-circle"></i>
        </a>
    </div>

    <div class="col-12 m-2 p-0"></div>

    <div class="form-group col-12 col-md-5 mb-3 mb-md-0"  wire:ignore>
        <label class="d-block" for="companyFilter">Filter by company</label>
        <select id="companyFilter" multiple class="filterSelector" data-width="fit" wire:model="filters.company_id" title="Noting selected" >
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
                    <th>MG Code</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Company</th>
                    <th>Client name</th>
                    <th>Subject</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($inquiries as $inquiry)
                <tr>
                    <td>{{$inquiry->getAttribute('code')}}</td>
                    <td>{{$inquiry->getAttribute('datetime')->format('d-m-Y')}}</td>
                    <td>{{$inquiry->getAttribute('datetime')->format('H:m')}}</td>
                    <td>{{$inquiry->getRelationValue('company')->getAttribute('name')}}</td>
                    <td>{{$inquiry->getAttribute('fullname')}}</td>
                    <td>{{$inquiry->getParameter('subject')}}</td>
                    <td>
                        <div class="btn-sm-group">
                            @if($trashBox)
                                @can('restore', $inquiry)
                                    <a href="{{route('inquiry.restore', $inquiry)}}" class="btn btn-sm btn-outline-primary" >
                                        <i class="fal fa-repeat"></i>
                                    </a>
                                @endcan
                                @can('forceDelete', $inquiry)
                                    <a href="{{route('inquiry.forceDelete', $inquiry)}}" delete data-name="{{$inquiry->code}}" class="btn btn-sm btn-outline-danger" >
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
                                    <a href="{{route('inquiry.destroy', $inquiry)}}" delete data-name="{{$inquiry->code}}" class="btn btn-sm btn-outline-danger" >
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




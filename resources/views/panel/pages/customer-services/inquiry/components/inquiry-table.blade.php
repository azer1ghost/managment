@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
@endsection

<div>
    <div class="float-right">
        <div class="p-2">
            @can('create', \App\Models\Inquiry::class)
                <a href="{{route('inquiry.create')}}" class="btn btn-outline-success">
                    <i class="fal fa-plus"></i>
                </a>
            @endcan
        </div>
    </div>
    <table class="table table-hover">
        <thead>
          <tr>
              <th>Date</th>
              <th>Company</th>
              <th>Client name</th>
              <th>Subject</th>
              <th>Actions</th>
          </tr>
        </thead>
        <tbody>
           @foreach($inquiries as $inquiry)
               <tr>
                   <td>{{$inquiry->date}}</td>
                   <td>{{$inquiry->company->name}}</td>
                   <td>{{$inquiry->fullname}}</td>
                   <td>{{$inquiry->subject->name}}</td>
                   <td>
                       <div class="btn-sm-group">
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
                               <a href="{{route('inquiry.destroy', $inquiry)}}" delete data-name="{{$inquiry->name}}" class="btn btn-sm btn-outline-danger" >
                                   <i class="fal fa-trash"></i>
                               </a>
                           @endcan
                           @can('restore', $inquiry)
                               <a href="{{route('inquiry.destroy', $inquiry)}}" delete data-name="{{$inquiry->name}}" class="btn btn-sm btn-outline-danger" >
                                   <i class="fal fa-trash"></i>
                               </a>
                           @endcan
                       </div>
                   </td>
               </tr>
           @endforeach
        </tbody>
    </table>
    <div class="float-right">
        {{ $inquiries->links() }}
    </div>
</div>





{{--    <select id="subjectFilter" multiple class="filterSelector" data-width="fit" title="Noting selected" >--}}
{{--        @foreach($subjects as $key => $subject)--}}
{{--            <option value="{{$key}}">{{ucfirst($subject->text)}}</option>--}}
{{--        @endforeach--}}
{{--    </select>--}}
{{--    <button class="btn btn-outline-secondary">--}}
{{--        <i class="fal fa-calendar"></i>--}}
{{--    </button>--}}
{{--    <input id="dateFilter" class="btn" type="date">--}}
{{--    <select class="btn" style="width: fit-content" id="locale">--}}
{{--        @foreach(config('app.locales') as $key => $locale)--}}
{{--            <option @if(app()->getLocale() === $key) selected @endif value="{{$key}}">{{ucfirst($locale)}}</option>--}}
{{--        @endforeach--}}
{{--    </select>--}}
{{--    <a href="{{route('inquiry.create')}}" class="btn btn-success">--}}
{{--        <i class="fal fa-plus"></i>--}}
{{--    </a>--}}




@section('scripts')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    {{--    <script>$('.filterSelector').selectpicker()</script>--}}

    {{--function edit(dataID){--}}
    {{--    window.location.href = "{{route('inquiry.edit', '%id%')}}".replace('%id%', dataID);--}}
    {{--}--}}

    {{--@if($errors->any())--}}
    {{--    $('#createModal').modal('show')--}}
    {{--@endif--}}

    {{--window.addEventListener('keypress', function (e) {--}}
    {{--    if (e.key === '+') {--}}
    {{--        $('#createModal').modal('show')--}}
    {{--    }--}}
    {{--}, false);--}}
@endsection
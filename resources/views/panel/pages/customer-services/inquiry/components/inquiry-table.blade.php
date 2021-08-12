@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
@endsection
<div class="component">
        <div class="row d-flex flex-column flex-md-row p-2">
            <div class="col mb-3 mb-md-0" wire:ignore>
                <select id="subjectFilter" multiple class="filterSelector" data-width="fit" wire:model="filters.subjects" title="Noting selected" >
                    @foreach($subjects as $subject)
                        <option value="{{$subject->id}}">{{ucfirst($subject->name)}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col d-flex">
                <input type="search" class="form-control mr-2" wire:model="filters.search">

                @can('create', \App\Models\Inquiry::class)
                    <a href="{{route('inquiry.create')}}" class="btn btn-outline-success">
                        <i class="fal fa-plus"></i>
                    </a>
                @endcan
            </div>
{{--            @can('restore', \App\Models\Inquiry::class)--}}
{{--                <a href="{{route('inquiry.create')}}" class="btn btn-outline-secondary">--}}
{{--                    <i class="far fa-recycle"></i>--}}
{{--                </a>--}}
{{--            @endcan--}}

        </div>

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
                   <td>{{$inquiry->code}}</td>
                   <td>{{$inquiry->datetime->format('d-m-Y')}}</td>
                   <td>{{$inquiry->datetime->format('H:m')}}</td>
                   <td>{{$inquiry->company->name}}</td>
                   <td>{{$inquiry->fullname}}</td>
                   <td>{{$inquiry->getParameter('subject')}}</td>
                   <td>
                       <div class="btn-sm-group">
                           @if($inquiry->trashed())
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
                                       <i class="fal fa-pen"></i></span>
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
    <div class="float-right">
        {{ $inquiries->links() }}
    </div>
</div>


@section('scripts')
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script>$('.filterSelector').selectpicker()</script>

    <script>
        function startTimer(duration, display) {
            let timer = duration, minutes, seconds;
            setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    timer = duration;
                    display.parentElement.remove()
                }
            }, 1000);
        }

        // window.onload = function () {
        //     let timer = document.querySelectorAll("span[data-time]");
        //     let delay = timer.getAttribute('data-time');
        //     startTimer(delay, timer);
        // };
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



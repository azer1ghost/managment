@extends('layouts.main')

@section('title', __('translates.navbar.calendar'))

@section('style')
    <link rel="stylesheet" href="{{asset('assets/css/fullcalendar.min.css')}}">
    <style>
        #calendar  a{
            color: #000;
        }
        .fc-content{
            padding: 3px;
        }
    </style>
@endsection

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.calendar')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <div class="row d-flex justify-content-between mb-2">
        <div class="col-12">
            <button class="btn btn-primary mb-3" type="button" data-toggle="collapse" data-target="#collapseEventTypes">
                @lang('translates.calendar.eventTypes')
            </button>
            <div class="collapse" id="collapseEventTypes">
                <div>
                    @foreach(\App\Models\Calendar::eventTypes() as $type)
                        <div class="d-flex mr-3">
                            <div style="border-radius:3px;width:20px;height:20px;background-color: {{$type['backgroundColor']}}" class="mr-1"></div>
                            <p>{{$type['name']}}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-12">
            @if($errors->any())
                {!! implode('', $errors->all('<div class="text-danger">:message</div>')) !!}
            @endif
        </div>
        <div class="col-12">
            <div id='calendar'></div>
        </div>
    </div>
    <div class="modal fade" id="date-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{route('calendars.store')}}" method="POST" id="calendar-form">
                    @csrf @method('')
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('translates.calendar.title')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name" id="data-name" class="form-control mb-3" placeholder="@lang('translates.calendar.fields.name')" required>
                        <input type="text" name="daterange" id="data-daterange" class="form-control mb-3 custom-daterange" readonly required>
                        <div class="form-group">
                            <select class="form-control" id="data-type" name="type" required>
                                <option value="" disabled selected>--@lang('translates.calendar.fields.select_type')--</option>
                                @foreach($types as $id => $type)
                                    <option value="{{$id}}">{{$type['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-1">
                            <input type="checkbox" name="is_day_off" value="1" id="data-is_day_off">
                            <label class="form-check-label" for="data-is_day_off">@lang('translates.calendar.fields.is_day_off')</label>
                        </div>
                        <div class="form-group">
                            <input type="checkbox" name="is_repeatable" value="1" id="data-is_repeatable">
                            <label class="form-check-label" for="data-is_repeatable">@lang('translates.calendar.fields.is_repeatable')</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('translates.buttons.close')</button>
                        <button type="submit" class="btn btn-primary">@lang('translates.buttons.save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('assets/js/fullcalendar/lib/moment.min.js')}}"></script>
    <script src="{{asset('assets/js/fullcalendar/fullcalendar.min.js')}}"></script>
    <script src="{{asset('assets/js/fullcalendar/locale/az.js')}}"></script>
    <script>

        const calendarForm = $('#calendar-form');

        $('#calendar').fullCalendar({
            eventLongPressDelay: 300,
            selectLongPressDelay: 300,
            locale: '{{app()->getLocale()}}',
            validRange: {
                end: '{{now()->endOfYear()->addYears(2)}}'
            },
            events: @json($events),
            @can('create', \App\Models\Calendar::class)
                selectable: true,
                select: function( start, end, jsEvent, view ){
                    $('#data-daterange').val(start.format() + ' - ' + end.format())
                    setTimeout(function() { $('#data-name').focus() }, 500);
                    $('#date-modal').modal('show')
                },
                dayClick: function(date, jsEvent, view) {
                    $('#data-daterange').val(date.format() + ' - ' + date.add(1, 'days').format());
                    setTimeout(function() { $('#data-name').focus() }, 500);
                    $('#date-modal').modal('show');
                },
            @endcan
            eventRender: function(event, element) {
                if (event.canDelete){
                    element.append("<span class='remove-event-btn' style='font-size: 15px'>X</span>");
                    element.addClass('d-flex').addClass('justify-content-between').addClass('align-items-center');

                    element.find(".remove-event-btn").css({'margin-right': 2, 'cursor': 'pointer'}).click(function (e) {
                        e.stopPropagation(); // to stop further event click event
                        $.confirm({
                            title: 'Confirm delete action',
                            content: `Are you sure to delete <b>${event.title}</b> ?`,
                            icon: 'fa fa-question',
                            type: 'red',
                            theme: 'modern',
                            typeAnimated: true,
                            buttons: {
                                confirm: function () {
                                    $('input[name="_method"]').val('DELETE');
                                    calendarForm.attr('action', '{{route('calendars.destroy', 'id')}}'.replace('id', event.id));
                                    calendarForm.submit();
                                    $.confirm({
                                        title: 'Delete successful',
                                        icon: 'fa fa-check',
                                        content: '<b>:name</b>'.replace(':name', event.title),
                                        type: 'blue',
                                        typeAnimated: true,
                                        theme: 'modern',
                                        buttons: false
                                    });
                                },
                                cancel: function () {
                                },
                            }
                        });
                    });
                }
            },
            eventClick: function(event, jsEvent, view) {
                if (event.canUpdate){
                    $('#data-daterange').val(event.start.format() + ' - ' + event.end.format());
                    $('input[name="_method"]').val('PUT');
                    $('#data-name').val(event.title);
                    $('#data-type').val(event.type);
                    $('#data-is_day_off').prop('checked', event.is_day_off);
                    $('#data-is_repeatable').prop('checked', event.is_repeatable);
                    calendarForm.attr('action', '{{route('calendars.update', 'id')}}'.replace('id', event.id));

                    setTimeout(function() { $('#data-name').focus() }, 500);
                    $('#date-modal').modal('show')
                }
            }
        })

        $('#date-modal').on('hidden.bs.modal', function (event) {
            calendarForm.attr('action', '{{route('calendars.store')}}');
            $('#data-daterange').val('');
            $('#data-name').val('');
            $('input[name="_method"]').val('');
            $('#data-type').val('');
            $('#data-is_day_off').prop('checked', false);
            $('#data-is_repeatable').prop('checked', false);
        })
    </script>
@endsection
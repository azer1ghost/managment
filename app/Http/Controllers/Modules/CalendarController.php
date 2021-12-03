<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\CalendarRequest;
use App\Models\Calendar;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Calendar::class, 'calendar');
    }

    public function index()
    {
        foreach (Calendar::withTrashed()->get() as $event) {
            $i = 1;
            if($event->isRepeatable() && is_null($event->getAttribute('deleted_at'))){
                while ($event->getAttribute('start_at')->addYears($i) < now()->addYears(3) &&
                    is_null(Calendar::withTrashed()->where('name', $event->getAttribute('name'))
                        ->where('start_at', $event->getAttribute('start_at')->addYears($i))
                        ->where('end_at', $event->getAttribute('end_at')->addYears($i))->first())
                ){
                    Calendar::create([
                        'name' => $event->getAttribute('name'),
                        'start_at' => $event->getAttribute('start_at')->addYears($i),
                        'end_at' => $event->getAttribute('end_at')->addYears($i),
                        'type' => (int) $event->getAttribute('type'),
                        'user_id' => $event->getAttribute('user_id'),
                        'is_day_off' => $event->getAttribute('is_day_off'),
                        'is_private' => $event->getAttribute('is_private'),
                        'is_repeatable' => $event->getAttribute('is_repeatable'),
                    ]);
                    $i++;
                }
            }
        }

        $events = Calendar::get()->map(function ($event){
            return [
                'id' => $event->getAttribute('id'),
                'title' => $event->getAttribute('name'),
                'type' => (int) $event->getAttribute('type'),
                'is_day_off' => $event->getAttribute('is_day_off'),
                'is_repeatable' => $event->getAttribute('is_repeatable'),
                'start' => $event->getAttribute('start_at')->addDay(),
                'end' => $event->getAttribute('end_at')->addDay(),
                'backgroundColor' => Calendar::eventTypes()[$event->getAttribute('type')]['backgroundColor'],
                'textColor' => Calendar::eventTypes()[$event->getAttribute('type')]['textColor'],
                'allDay' => true,
                'canDelete' => auth()->user()->can('delete', $event),
                'canUpdate' => auth()->user()->can('update', $event),
            ];
        });

        return view('panel.pages.calendar.index')->with([
            'events' => $events,
            'types' => Calendar::eventTypes()
        ]);
    }

    public function store(CalendarRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();
        list($validated['start_at'], $validated['end_at']) = explode(' - ', $validated['daterange']);

        Calendar::create($validated);

        return back();
    }

    public function update(CalendarRequest $request, Calendar $calendar)
    {
        $validated = $request->validated();
        $validated['is_day_off'] = $request->has('is_day_off');
        $validated['is_repeatable'] = $request->has('is_repeatable');

        $calendar->update($validated);

        return back();
    }

    public function destroy(Calendar $calendar)
    {
        if ($calendar->delete()) {
            return back();
        }

        return back()->withNotify('error', $calendar->getAttribute('name'));
    }
}

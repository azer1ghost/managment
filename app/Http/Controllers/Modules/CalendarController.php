<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\CalendarRequest;
use App\Models\Calendar;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index()
    {
        $calendars = Calendar::get();

        $events = $calendars->map(function ($event){
            return [
                'id' => $event->getAttribute('id'),
                'title' => $event->getAttribute('name'),
                'type' => (int) $event->getAttribute('type'),
                'is_day_off' => $event->getAttribute('is_day_off'),
                'is_repeatable' => $event->getAttribute('is_repeatable'),
                'start' => $event->getAttribute('start_at')->addDay(),
                'end' => $event->getAttribute('end_at')->addDay(),
                'backgroundColor' => Calendar::types()[$event->getAttribute('type')]['backgroundColor'],
                'textColor' => Calendar::types()[$event->getAttribute('type')]['textColor'],
                'allDay' => true,
            ];
        });

        $recurring_events = [];
        foreach ($calendars as $event) {
            if($event->isRepeatable()){
                $recurring_events[] = [
                    'id' => $event->getAttribute('id'),
                    'title' => $event->getAttribute('name'),
                    'type' => (int) $event->getAttribute('type'),
                    'is_day_off' => $event->getAttribute('is_day_off'),
                    'is_repeatable' => $event->getAttribute('is_repeatable'),
                    'start' => $event->getAttribute('start_at')->addDay()->addYear(),
                    'end' => $event->getAttribute('end_at')->addDay()->addYear(),
                    'backgroundColor' => Calendar::types()[$event->getAttribute('type')]['backgroundColor'],
                    'textColor' => Calendar::types()[$event->getAttribute('type')]['textColor'],
                    'allDay' => true,
                ];
            }
        }

        return view('panel.pages.calendar.index')->with([
            'events' => array_merge($events->toArray(), $recurring_events),
            'types' => Calendar::types()
        ]);
    }

    public function store(CalendarRequest $request)
    {
        $validated = $request->validated();
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

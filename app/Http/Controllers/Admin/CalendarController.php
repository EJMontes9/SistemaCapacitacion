<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Course;

class CalendarController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        $events = Event::with('course')->get();
        return view('admin.calendar.index', compact('courses', 'events'));
    }

    public function events()
    {
        $events = Event::with('course', 'user')->get()->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->event_date . ($event->event_time ? 'T' . $event->event_time : ''),
                'end' => $event->end_date,
                'color' => $event->color,
                'description' => $event->description,
                'location' => $event->location,
                'type' => $event->type,
                'allDay' => $event->is_all_day,
                'course' => $event->course ? $event->course->title : null,
            ];
        });

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable|date_format:H:i',
            'end_date' => 'nullable|date|after_or_equal:event_date',
            'location' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'type' => 'required|in:class,exam,deadline,meeting,other',
            'is_all_day' => 'boolean',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        $validated['user_id'] = auth()->id();

        Event::create($validated);

        return redirect()->route('admin.calendar.index')->with('info', 'Evento creado con éxito.');
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable|date_format:H:i',
            'end_date' => 'nullable|date|after_or_equal:event_date',
            'location' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:7',
            'type' => 'required|in:class,exam,deadline,meeting,other',
            'is_all_day' => 'boolean',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        $event->update($validated);

        return redirect()->route('admin.calendar.index')->with('info', 'Evento actualizado con éxito.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.calendar.index')->with('info', 'Evento eliminado con éxito.');
    }

    public function studentIndex()
    {
        $user = auth()->user();
        $courseIds = $user->courses()->pluck('courses.id');
        $events = Event::with('course')
            ->where(function ($q) use ($courseIds) {
                $q->whereIn('course_id', $courseIds)->orWhereNull('course_id');
            })
            ->orderBy('event_date')
            ->get();

        return view('calendar.public', compact('events'));
    }
}

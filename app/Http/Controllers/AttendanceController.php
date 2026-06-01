<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassSession;
use App\Models\Course;
use App\Models\Section;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $sessions = ClassSession::with('course')->orderBy('session_date', 'desc')->paginate(15);
        return view('admin.attendance.index', compact('sessions'));
    }

    public function create()
    {
        $courses = Course::all();
        $sections = Section::all();
        $lessons = Lesson::all();
        return view('admin.attendance.create', compact('courses', 'sections', 'lessons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'section_id' => 'nullable|exists:sections,id',
            'lesson_id' => 'nullable|exists:lessons,id',
            'title' => 'required|string|max:255',
            'session_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'duration' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        ClassSession::create($validated);

        return redirect()->route('admin.attendance.index')->with('info', 'Sesión creada correctamente');
    }

    public function show($id)
    {
        $session = ClassSession::with(['course', 'section', 'lesson', 'attendance.user'])->findOrFail($id);
        $students = User::whereHas('roles', function ($q) {
            $q->where('name', 'Alumno');
        })->whereHas('courses', function ($q) use ($session) {
            $q->where('course_id', $session->course_id);
        })->get();
        return view('admin.attendance.show', compact('session', 'students'));
    }

    public function edit($id)
    {
        $session = ClassSession::findOrFail($id);
        $courses = Course::all();
        $sections = Section::all();
        $lessons = Lesson::all();
        return view('admin.attendance.create', compact('session', 'courses', 'sections', 'lessons'));
    }

    public function update(Request $request, $id)
    {
        $session = ClassSession::findOrFail($id);
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'section_id' => 'nullable|exists:sections,id',
            'lesson_id' => 'nullable|exists:lessons,id',
            'title' => 'required|string|max:255',
            'session_date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'duration' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $session->update($validated);

        return redirect()->route('admin.attendance.index')->with('info', 'Sesión actualizada correctamente');
    }

    public function destroy($id)
    {
        $session = ClassSession::findOrFail($id);
        $session->attendance()->delete();
        $session->delete();

        return redirect()->route('admin.attendance.index')->with('info', 'Sesión eliminada correctamente');
    }

    public function markAttendance(Request $request, $sessionId)
    {
        $request->validate([
            'attendance' => 'required|array',
            'attendance.*.user_id' => 'required|exists:users,id',
            'attendance.*.status' => 'required|in:present,absent,late',
        ]);

        $session = ClassSession::findOrFail($sessionId);

        foreach ($request->attendance as $record) {
            Attendance::updateOrCreate(
                [
                    'class_session_id' => $session->id,
                    'user_id' => $record['user_id'],
                ],
                [
                    'status' => $record['status'],
                    'comment' => $record['comment'] ?? null,
                ]
            );
        }

        return redirect()->route('admin.attendance.show', $sessionId)->with('info', 'Asistencia registrada correctamente');
    }

    public function getCourseAttendance($courseId)
    {
        $course = Course::findOrFail($courseId);
        $sessions = ClassSession::where('course_id', $courseId)->with('attendance')->get();
        $totalSessions = $sessions->count();
        $students = User::whereHas('roles', function ($q) {
            $q->where('name', 'Alumno');
        })->whereHas('courses', function ($q) use ($courseId) {
            $q->where('course_id', $courseId);
        })->get();

        $stats = $students->map(function ($student) use ($sessions) {
            $present = 0;
            $absent = 0;
            $late = 0;
            foreach ($sessions as $session) {
                $att = $session->attendance->where('user_id', $student->id)->first();
                if ($att) {
                    if ($att->status === 'present') $present++;
                    elseif ($att->status === 'absent') $absent++;
                    elseif ($att->status === 'late') $late++;
                }
            }
            return [
                'user_id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'present' => $present,
                'absent' => $absent,
                'late' => $late,
                'total' => $sessions->count(),
            ];
        });

        return response()->json([
            'course' => $course,
            'total_sessions' => $totalSessions,
            'students' => $stats,
        ]);
    }

    public function getStudentAttendance($courseId, $userId)
    {
        $sessions = ClassSession::where('course_id', $courseId)
            ->with(['attendance' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }])
            ->orderBy('session_date', 'asc')
            ->get();

        return response()->json([
            'user' => User::findOrFail($userId),
            'sessions' => $sessions,
        ]);
    }

    public function myAttendance()
    {
        $user = auth()->user();
        $courses = $user->courses()->with('classSessions.attendance')->get();
        return view('attendance.my-index', compact('courses', 'user'));
    }

    public function myAttendanceByCourse($courseId)
    {
        $user = auth()->user();
        $course = Course::findOrFail($courseId);
        $sessions = ClassSession::where('course_id', $courseId)
            ->with(['attendance' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }])
            ->orderBy('session_date', 'asc')
            ->get();
        return view('attendance.my-course', compact('course', 'sessions', 'user'));
    }

    public function courseAttendanceDashboard($courseId)
    {
        $course = Course::with('classSessions.attendance')->findOrFail($courseId);
        $students = User::whereHas('roles', function ($q) {
            $q->where('name', 'Alumno');
        })->whereHas('courses', function ($q) use ($courseId) {
            $q->where('course_id', $courseId);
        })->get();

        $stats = $students->map(function ($student) use ($course) {
            $present = 0; $absent = 0; $late = 0;
            foreach ($course->classSessions as $session) {
                $att = $session->attendance->where('user_id', $student->id)->first();
                if ($att) {
                    if ($att->status === 'present') $present++;
                    elseif ($att->status === 'absent') $absent++;
                    elseif ($att->status === 'late') $late++;
                }
            }
            return [
                'user_id' => $student->id, 'name' => $student->name, 'email' => $student->email,
                'present' => $present, 'absent' => $absent, 'late' => $late,
                'total' => $course->classSessions->count(),
                'percentage' => $course->classSessions->count() > 0
                    ? round(($present / $course->classSessions->count()) * 100, 1) : 0,
            ];
        });

        return view('attendance.course-dashboard', compact('course', 'students', 'stats'));
    }
}

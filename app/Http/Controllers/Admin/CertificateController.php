<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Course;
use App\Models\User;
use App\Models\Attendance;
use App\Models\EvaluationResult;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $courses = $this->userIsAdmin($user)
            ? Course::all()
            : Course::where('user_id', $user->id)->get();

        $templates = CertificateTemplate::with('course')->get()->keyBy('course_id');

        return view('admin.certificates.index', compact('courses', 'templates'));
    }

    public function createTemplate()
    {
        $user = auth()->user();
        $courses = $this->userIsAdmin($user)
            ? Course::all()
            : Course::where('user_id', $user->id)->get();

        $courses = $courses->filter(function ($course) {
            return !CertificateTemplate::where('course_id', $course->id)->exists();
        });

        $defaultHtml = view('admin.certificates.default-template', [
            'userName' => '@studentName',
            'courseTitle' => '@courseName',
            'completionDate' => '@completionDate',
            'certificateCode' => '@certificateCode',
        ])->render();

        return view('admin.certificates.create', compact('courses', 'defaultHtml'));
    }

    public function storeTemplate(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'body_html' => 'required|string',
            'min_attendance' => 'nullable|integer|min:0|max:100',
            'min_grade' => 'nullable|integer|min:0|max:100',
        ]);

        $exists = CertificateTemplate::where('course_id', $request->course_id)->exists();
        if ($exists) {
            return redirect()->route('admin.certificates.index')->with('info', 'Ya existe una plantilla para este curso.');
        }

        CertificateTemplate::create([
            'course_id' => $request->course_id,
            'body_html' => $request->body_html,
            'min_attendance' => $request->min_attendance ?: 80,
            'min_grade' => $request->min_grade ?: 70,
        ]);

        return redirect()->route('admin.certificates.index')->with('info', 'Plantilla de certificado creada con éxito.');
    }

    public function showTemplate(CertificateTemplate $template)
    {
        $template->load('course');
        return view('admin.certificates.show', compact('template'));
    }

    public function editTemplate(CertificateTemplate $template)
    {
        $template->load('course');
        return view('admin.certificates.edit', compact('template'));
    }

    public function updateTemplate(Request $request, CertificateTemplate $template)
    {
        $request->validate([
            'body_html' => 'required|string',
            'min_attendance' => 'nullable|integer|min:0|max:100',
            'min_grade' => 'nullable|integer|min:0|max:100',
        ]);

        $template->update([
            'body_html' => $request->body_html,
            'min_attendance' => $request->min_attendance ?: 80,
            'min_grade' => $request->min_grade ?: 70,
        ]);

        return redirect()->route('admin.certificates.index')->with('info', 'Plantilla actualizada con éxito.');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'body_html' => 'required|string',
        ]);

        $previewData = [
            'studentName' => $request->student_name ?: 'María García López',
            'courseName' => $request->course_name ?: 'Curso de Ejemplo',
            'completionDate' => $this->formatDate(now()),
            'certificateCode' => 'CERT-PREVIEW-0001',
        ];

        $html = CertificateTemplate::previewHtml($request->body_html, $previewData);

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['isRemoteEnabled' => true]);
        $pdf->render();

        return $pdf->stream('preview.pdf');
    }

    public function generateForCourse(CertificateTemplate $template)
    {
        $template->load('course');
        $course = $template->course;
        $students = $course->students()->role('alumno')->get();

        $minAttendance = (int) $template->min_attendance ?: 80;
        $minGrade = (int) $template->min_grade ?: 70;

        $totalSessions = $course->classSessions()->count();
        $count = 0;
        $skipped = [];

        foreach ($students as $student) {
            $exists = Certificate::where('course_id', $course->id)
                ->where('user_id', $student->id)
                ->exists();
            if ($exists) continue;

            // Check attendance
            if ($totalSessions > 0) {
                $attended = \App\Models\Attendance::where('user_id', $student->id)
                    ->whereIn('class_session_id', $course->classSessions()->pluck('id'))
                    ->where('status', 'present')
                    ->count();
                $attPct = ($attended / $totalSessions) * 100;
                if ($attPct < $minAttendance) {
                    $skipped[] = "{$student->name} (asistencia {$attPct}% < {$minAttendance}%)";
                    continue;
                }
            }

            // Check grade
            $avgGrade = (int) \App\Models\EvaluationResult::where('course_id', $course->id)
                ->where('user_id', $student->id)
                ->avg('total_score');
            if ($avgGrade < $minGrade) {
                $skipped[] = "{$student->name} (nota {$avgGrade} < {$minGrade})";
                continue;
            }

            $code = Certificate::generateCode($course->id, $student->id);
            $previewData = [
                'studentName' => $student->name,
                'courseName' => $course->title,
                'completionDate' => $this->formatDate(now()),
                'certificateCode' => $code,
            ];
            $html = CertificateTemplate::previewHtml($template->body_html, $previewData);

            Certificate::create([
                'course_id' => $course->id,
                'user_id' => $student->id,
                'completion_date' => now(),
                'certificate_code' => $code,
                'grade' => $avgGrade ?: null,
                'status' => 'approved',
                'issued_by' => auth()->id(),
                'metadata' => json_encode([
                    'course_title' => $course->title,
                    'user_name' => $student->name,
                    'user_email' => $student->email,
                ]),
                'body_html' => $html,
            ]);
            $count++;
        }

        $msg = "$count certificados generados para: $course->title.";
        if ($skipped) {
            $msg .= ' Omitidos: ' . implode('; ', $skipped);
        }
        return redirect()->route('admin.certificates.index')->with('info', $msg);
    }

    public function download(Certificate $certificate)
    {
        $certificate->load('course', 'user', 'issuer');

        $previewData = [
            'studentName' => $certificate->user->name,
            'courseName' => $certificate->course->title,
            'completionDate' => $this->formatDate($certificate->completion_date),
            'certificateCode' => $certificate->certificate_code,
        ];

        $template = CertificateTemplate::where('course_id', $certificate->course_id)->first();

        if ($template) {
            $html = CertificateTemplate::previewHtml($template->body_html, $previewData);
        } else {
            $html = view('admin.certificates.default-template', [
                'userName' => '@studentName',
                'courseTitle' => '@courseName',
                'completionDate' => '@completionDate',
                'certificateCode' => '@certificateCode',
            ])->render();
            $html = CertificateTemplate::previewHtml($html, $previewData);
        }

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['isRemoteEnabled' => true]);
        $pdf->render();

        return $pdf->download('certificado-' . $certificate->certificate_code . '.pdf');
    }

    public function verify($code)
    {
        $certificate = Certificate::with('course', 'user', 'issuer')
            ->where('certificate_code', $code)
            ->first();

        if (!$certificate) {
            return view('certificates.public-verify', [
                'valid' => false,
                'certificate' => null,
                'message' => 'El código de certificado no es válido.',
            ]);
        }

        if ($certificate->status !== 'approved') {
            return view('certificates.public-verify', [
                'valid' => false,
                'certificate' => $certificate,
                'message' => 'Este certificado no ha sido aprobado.',
            ]);
        }

        return view('certificates.public-verify', [
            'valid' => true,
            'certificate' => $certificate,
            'message' => null,
        ]);
    }

    private function userIsAdmin(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_type', User::class)
            ->where('model_has_roles.model_id', $user->id)
            ->where('roles.name', 'Admin')
            ->exists();
    }

    private function formatDate($value): string
    {
        if ($value instanceof \DateTimeInterface) {
            return $value->format('d/m/Y');
        }

        return date('d/m/Y', strtotime((string) $value));
    }
}

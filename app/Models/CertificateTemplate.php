<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    protected $fillable = ['course_id', 'body_html', 'min_attendance', 'min_grade'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public static function previewHtml($bodyHtml, $previewData = [])
    {
        $replacements = [
            '@studentName' => $previewData['studentName'] ?? '[Nombre del Estudiante]',
            '@courseName' => $previewData['courseName'] ?? '[Nombre del Curso]',
            '@completionDate' => $previewData['completionDate'] ?? now()->format('d/m/Y'),
            '@certificateCode' => $previewData['certificateCode'] ?? 'CERT-XXXXXXXX',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $bodyHtml);
    }
}

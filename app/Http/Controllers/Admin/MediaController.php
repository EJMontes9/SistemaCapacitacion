<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaFile;
use App\Models\CourseQuota;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = MediaFile::query();

        if ($request->course_id) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        $files = $query->orderBy('created_at', 'desc')->paginate(20);
        $courses = Course::pluck('title', 'id');

        $quota = null;
        if ($request->course_id) {
            $quota = CourseQuota::firstOrCreate(
                ['course_id' => $request->course_id],
                ['max_size' => 524288000, 'max_files' => 100]
            );
        }

        return view('admin.media.index', compact('files', 'courses', 'quota'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:102400',
            'course_id' => 'nullable|exists:courses,id',
            'type' => 'nullable|string|in:image,video,audio,document,other',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();
        $type = $request->type ?? $this->guessType($mimeType);

        if ($request->course_id) {
            $quota = CourseQuota::firstOrCreate(
                ['course_id' => $request->course_id],
                ['max_size' => 524288000, 'max_files' => 100]
            );

            if (!$quota->hasSpaceFor($size)) {
                return back()->with('error', 'El curso ha alcanzado su cuota de almacenamiento.');
            }
        }

        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('uploads/' . ($request->course_id ?? 'general'), $filename, 'public');

        $mediaFile = MediaFile::create([
            'course_id' => $request->course_id,
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'original_name' => $originalName,
            'filename' => $filename,
            'path' => $path,
            'type' => $type,
            'mime_type' => $mimeType,
            'size' => $size,
            'disk' => 'public',
        ]);

        // Generate thumbnail for images using GD
        if ($type === 'image' && extension_loaded('gd')) {
            try {
                $imgPath = Storage::disk('public')->path($path);
                $thumbFilename = 'thumb_' . $filename;
                $thumbPath = 'uploads/' . ($request->course_id ?? 'general') . '/' . $thumbFilename;

                $info = getimagesize($imgPath);
                if ($info) {
                    $width = $info[0];
                    $height = $info[1];
                    $thumbWidth = 300;
                    $thumbHeight = intval($height * ($thumbWidth / $width));

                    $srcImage = match ($info[2]) {
                        IMAGETYPE_JPEG => imagecreatefromjpeg($imgPath),
                        IMAGETYPE_PNG => imagecreatefrompng($imgPath),
                        IMAGETYPE_GIF => imagecreatefromgif($imgPath),
                        IMAGETYPE_WEBP => imagecreatefromwebp($imgPath),
                        default => null,
                    };

                    if ($srcImage) {
                        $thumbImage = imagecreatetruecolor($thumbWidth, $thumbHeight);
                        imagecopyresampled($thumbImage, $srcImage, 0, 0, 0, 0, $thumbWidth, $thumbHeight, $width, $height);

                        $thumbFullPath = Storage::disk('public')->path($thumbPath);
                        $ext = strtolower($file->getClientOriginalExtension());

                        match ($ext) {
                            'png' => imagepng($thumbImage, $thumbFullPath),
                            'gif' => imagegif($thumbImage, $thumbFullPath),
                            'webp' => imagewebp($thumbImage, $thumbFullPath),
                            default => imagejpeg($thumbImage, $thumbFullPath, 80),
                        };

                        imagedestroy($thumbImage);
                        imagedestroy($srcImage);

                        $mediaFile->update([
                            'thumbnail_path' => $thumbPath,
                            'is_compressed' => true,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                // Thumbnail generation failed, continue without
            }
        }

        if (isset($quota)) {
            $quota->addSize($size);
        }

        return redirect()->route('admin.media.index', $request->course_id ? ['course_id' => $request->course_id] : [])
            ->with('info', 'Archivo subido correctamente.');
    }

    public function download($id)
    {
        $file = MediaFile::findOrFail($id);
        $file->increment('downloads_count');
        return Storage::disk($file->disk)->download($file->path, $file->original_name);
    }

    public function stream($id)
    {
        $file = MediaFile::findOrFail($id);
        $file->increment('downloads_count');
        $path = Storage::disk($file->disk)->path($file->path);
        return response()->file($path, [
            'Content-Type' => $file->mime_type,
            'Content-Disposition' => 'inline; filename="' . $file->original_name . '"',
        ]);
    }

    public function destroy($id)
    {
        $file = MediaFile::findOrFail($id);
        Storage::disk($file->disk)->delete($file->path);

        if ($file->thumbnail_path) {
            Storage::disk($file->disk)->delete($file->thumbnail_path);
        }

        if ($file->course_id) {
            $quota = CourseQuota::where('course_id', $file->course_id)->first();
            if ($quota) {
                $quota->removeSize($file->size);
            }
        }

        $file->delete();

        return redirect()->route('admin.media.index')->with('info', 'Archivo eliminado.');
    }

    private function guessType($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }
        if (str_starts_with($mimeType, 'video/')) {
            return 'video';
        }
        if (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        }
        if (in_array($mimeType, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
        ])) {
            return 'document';
        }
        return 'other';
    }
}

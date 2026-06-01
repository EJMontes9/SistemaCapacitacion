<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.branding.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'site_description' => 'nullable|string|max:500',
            'site_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'site_favicon' => 'nullable|image|mimes:png,ico,jpg,jpeg,svg,webp|max:1024',
            'login_bg' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5120',
        ]);

        Setting::setValue('site_name', $request->site_name);
        Setting::setValue('primary_color', $request->primary_color, 'color');
        Setting::setValue('secondary_color', $request->secondary_color, 'color');
        Setting::setValue('site_description', $request->site_description);

        $imageFields = ['site_logo', 'site_favicon', 'login_bg'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = $field . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('branding', $filename, 'public');
                Setting::setValue($field, $path, 'image');
            }
        }

        return redirect()->route('admin.branding.index')
            ->with('info', 'Configuración de marca actualizada correctamente.');
    }

    public function reset()
    {
        $defaults = [
            'site_name' => 'StudyApp',
            'site_description' => 'Plataforma de Capacitación',
            'primary_color' => '#3B82F6',
            'secondary_color' => '#1E40AF',
            'site_logo' => null,
            'site_favicon' => null,
            'login_bg' => null,
        ];

        foreach ($defaults as $key => $value) {
            Setting::where('key', $key)->delete();
        }

        return redirect()->route('admin.branding.index')
            ->with('info', 'Valores restablecidos a los predeterminados.');
    }
}

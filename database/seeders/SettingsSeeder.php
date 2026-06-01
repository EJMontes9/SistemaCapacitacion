<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'StudyApp', 'type' => 'text', 'group' => 'general'],
            ['key' => 'site_logo', 'value' => null, 'type' => 'image', 'group' => 'general'],
            ['key' => 'site_favicon', 'value' => null, 'type' => 'image', 'group' => 'general'],
            ['key' => 'primary_color', 'value' => '#3B82F6', 'type' => 'color', 'group' => 'general'],
            ['key' => 'secondary_color', 'value' => '#1E40AF', 'type' => 'color', 'group' => 'general'],
            ['key' => 'login_bg', 'value' => null, 'type' => 'image', 'group' => 'general'],
            ['key' => 'site_description', 'value' => 'Plataforma de Capacitación', 'type' => 'text', 'group' => 'general'],
            ['key' => 'cert_min_attendance', 'value' => '80', 'type' => 'number', 'group' => 'certificates'],
            ['key' => 'cert_min_grade', 'value' => '70', 'type' => 'number', 'group' => 'certificates'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}

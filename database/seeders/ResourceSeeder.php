<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resource;

class ResourceSeeder extends Seeder
{
    public function run()
    {
        Resource::create([
            'name' => 'Documento de ejemplo',
            'url' => 'https://ejemplo.com/documento.pdf',
            'type' => 'pdf',
            'section_id' => 1, // Asegúrate de que este ID de sección exista
        ]);

        Resource::create([
            'name' => 'Video tutorial',
            'url' => 'https://ejemplo.com/video.mp4',
            'type' => 'video',
            'section_id' => 1,
        ]);

        // Puedes agregar más recursos de ejemplo aquí
    }
}
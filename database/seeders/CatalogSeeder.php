<?php

namespace Database\Seeders;

use App\Models\Catalog;
use App\Models\CatalogItem;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $modalidades = Catalog::updateOrCreate(
            ['slug' => 'modalidades'],
            ['name' => 'Modalidades', 'description' => 'Modalidades de curso']
        );

        $modalidadItems = [
            ['name' => 'Presencial', 'value' => 'presencial', 'code' => 'PRE', 'order' => 1],
            ['name' => 'Virtual', 'value' => 'virtual', 'code' => 'VIR', 'order' => 2],
            ['name' => 'Semipresencial', 'value' => 'semipresencial', 'code' => 'SEM', 'order' => 3],
        ];
        foreach ($modalidadItems as $item) {
            $item['catalog_id'] = $modalidades->id;
            $item['is_active'] = true;
            CatalogItem::updateOrCreate(
                ['catalog_id' => $modalidades->id, 'name' => $item['name']],
                $item
            );
        }

        $periodos = Catalog::updateOrCreate(
            ['slug' => 'periodos'],
            ['name' => 'Periodos', 'description' => 'Periodos académicos']
        );

        $periodoItems = [
            ['name' => 'Mensual', 'value' => 'mensual', 'code' => 'M', 'order' => 1],
            ['name' => 'Bimestral', 'value' => 'bimestral', 'code' => 'B', 'order' => 2],
            ['name' => 'Trimestral', 'value' => 'trimestral', 'code' => 'T', 'order' => 3],
            ['name' => 'Semestral', 'value' => 'semestral', 'code' => 'S', 'order' => 4],
        ];
        foreach ($periodoItems as $item) {
            $item['catalog_id'] = $periodos->id;
            $item['is_active'] = true;
            CatalogItem::updateOrCreate(
                ['catalog_id' => $periodos->id, 'name' => $item['name']],
                $item
            );
        }

        $sedes = Catalog::updateOrCreate(
            ['slug' => 'sedes'],
            ['name' => 'Sedes', 'description' => 'Sedes de la institución']
        );

        $sedeItems = [
            ['name' => 'Central', 'value' => 'central', 'code' => 'CEN', 'order' => 1],
            ['name' => 'Norte', 'value' => 'norte', 'code' => 'NOR', 'order' => 2],
            ['name' => 'Sur', 'value' => 'sur', 'code' => 'SUR', 'order' => 3],
        ];
        foreach ($sedeItems as $item) {
            $item['catalog_id'] = $sedes->id;
            $item['is_active'] = true;
            CatalogItem::updateOrCreate(
                ['catalog_id' => $sedes->id, 'name' => $item['name']],
                $item
            );
        }

        $niveles = Catalog::updateOrCreate(
            ['slug' => 'niveles'],
            ['name' => 'Niveles', 'description' => 'Niveles de dificultad del curso']
        );

        $nivelItems = [
            ['name' => 'Básico', 'value' => 'basico', 'code' => 'BAS', 'order' => 1],
            ['name' => 'Intermedio', 'value' => 'intermedio', 'code' => 'INT', 'order' => 2],
            ['name' => 'Avanzado', 'value' => 'avanzado', 'code' => 'AVA', 'order' => 3],
        ];
        foreach ($nivelItems as $item) {
            $item['catalog_id'] = $niveles->id;
            $item['is_active'] = true;
            CatalogItem::updateOrCreate(
                ['catalog_id' => $niveles->id, 'name' => $item['name']],
                $item
            );
        }
    }
}

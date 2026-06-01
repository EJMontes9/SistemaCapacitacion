<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menu = Menu::updateOrCreate(
            ['slug' => 'main'],
            ['name' => 'Principal', 'description' => 'Menú principal de navegación']
        );

        $items = [
            ['title' => 'Dashboard', 'url' => '/dashboard', 'icon' => 'fas fa-tachometer-alt', 'roles' => null, 'order' => 1],
            ['title' => 'Cursos', 'url' => '/listcourse', 'icon' => 'fas fa-book', 'roles' => null, 'order' => 2],
            ['title' => 'Crear Curso', 'url' => '/courses/create', 'icon' => 'fas fa-plus-circle', 'roles' => 'Instructor,Admin', 'order' => 3],
            ['title' => 'Encuestas', 'url' => '/surveys', 'icon' => 'fas fa-poll', 'roles' => 'Instructor', 'order' => 4],
            ['title' => 'Mis Evaluaciones', 'url' => '/evaluations', 'icon' => 'fas fa-tasks', 'roles' => 'Instructor', 'order' => 5],
            ['title' => 'Libro de Calificaciones', 'route' => 'evaluations.gradebook.index', 'icon' => 'fas fa-chart-bar', 'roles' => 'Instructor,Admin', 'order' => 6],
            ['title' => 'Asistencia', 'url' => '/admin/attendance', 'icon' => 'fas fa-clipboard-check', 'roles' => 'Instructor,Admin', 'order' => 7],
            ['title' => 'Calendario', 'url' => '/admin/calendar', 'icon' => 'fas fa-calendar-alt', 'roles' => 'Instructor,Admin', 'order' => 8],
            ['title' => 'Mi Calendario', 'url' => '/calendar', 'icon' => 'fas fa-calendar-alt', 'roles' => 'Alumno', 'order' => 9],
            ['title' => 'Anuncios', 'url' => '/admin/announcements', 'icon' => 'fas fa-bullhorn', 'roles' => 'Instructor,Admin', 'order' => 10],
            ['title' => 'Multimedia', 'url' => '/admin/media', 'icon' => 'fas fa-photo-video', 'roles' => 'Instructor,Admin', 'order' => 11],
            ['title' => 'Mi Asistencia', 'url' => '/my-attendance', 'icon' => 'fas fa-clipboard-list', 'roles' => 'Alumno', 'order' => 12],
            ['title' => 'Certificados', 'url' => '/admin/certificates', 'icon' => 'fas fa-certificate', 'roles' => 'Instructor,Admin', 'order' => 13],
            ['title' => 'Panel Admin', 'route' => 'admin.home', 'icon' => 'fas fa-shield-alt', 'roles' => 'Admin', 'order' => 14],
            ['title' => 'Categorías', 'route' => 'admin.categories.index', 'icon' => 'fas fa-window-restore', 'roles' => 'Admin', 'order' => 15],
            ['title' => 'Roles', 'route' => 'admin.roles.index', 'icon' => 'fas fa-user-cog', 'roles' => 'Admin', 'order' => 16],
            ['title' => 'Usuarios', 'route' => 'admin.users.index', 'icon' => 'fas fa-users', 'roles' => 'Admin', 'order' => 17],
            ['title' => 'Catálogos', 'route' => 'admin.catalogs.index', 'icon' => 'fas fa-list', 'roles' => 'Admin', 'order' => 18],
            ['title' => 'Menús', 'route' => 'admin.menus.index', 'icon' => 'fas fa-bars', 'roles' => 'Admin', 'order' => 19],
            ['title' => 'Branding', 'route' => 'admin.branding.index', 'icon' => 'fas fa-paint-brush', 'roles' => 'Admin', 'order' => 20],
            ['title' => 'Mi Perfil', 'route' => 'profile.show', 'icon' => 'fas fa-user', 'roles' => null, 'order' => 21],
        ];

        MenuItem::where('menu_id', $menu->id)->delete();

        foreach ($items as $i => $itemData) {
            $itemData['menu_id'] = $menu->id;
            MenuItem::create($itemData);
        }
    }
}

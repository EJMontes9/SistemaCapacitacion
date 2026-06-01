# Seguimiento de mejoras (ejecucion)
Actualizado: 2026-05-31

## Estado global
- En progreso: 0
- Completados: 15
- Pendientes: 4

## Backlog con estado
| ID | Estado | Item | Notas |
|---|---|---|---|
| P0-01 | Completado | Unificar modelo Course | Eliminado `courses.php`, unificado en `Course.php`. Actualizadas 20+ referencias. |
| P0-02 | Completado | Ajuste modules | Migracion `add_fields_to_modules_table`, modelo Module con $fillable. |
| P1-01 | Completado | Asistencia | Migraciones class_sessions + attendance. Modelos, controlador con API + vistas web. Rutas web/api agregadas. |
| P1-02 | Completado | CRUD alumnos/docentes | Migracion perfiles academicos. UserController expandido (create/store/show/destroy). Vistas create/show. User model actualizado. |
| P1-03 | Completado | Importacion CSV | ImportController con carga CSV. Vista admin/import. Ruta en admin.php. |
| P1-04 | Completado | Periodos configurables | Migracion academic_periods + campos en courses. Modelo AcademicPeriod. CRUD admin con vistas. |
| P2-01 | Completado | Intentos evaluacion | Migracion evaluation_attempts + campos max_attempts/time_limit. Modelo EvaluationAttempt. |
| P2-02 | Completado | Banco de preguntas | Migraciones question_bank + question_bank_options. Modelos QuestionBank, QuestionBankOption. |
| P2-03 | Completado | Gradebook | GradebookController con vista y exportacion CSV. Ruta web. |
| P3-01 | Completado | Almacenamiento local | ResourceController actualizado con validacion extensiones y storage local. Migracion file fields. |
| P4-01 | Completado | Calendario/avisos | Migracion announcements. Modelo, controller admin, vistas index/create/edit. Rutas admin. |
| P4-02 | Completado | Certificados | Migracion certificates. Modelo, controller admin, vistas index/create. Rutas admin. |
| V1-01 | Pendiente | Panel de branding | Settings + assets locales |
| V1-02 | Pendiente | Tema global | Variables CSS/Tailwind |
| V2-01 | Pendiente | Menu configurable | Menu DB + render dinamico |
| V2-02 | Pendiente | Submenus y permisos | Jerarquia y roles |
| V3-01 | Completado | Modulo de catalogos | Migraciones catalogs + catalog_items. Modelos, controller admin, vistas index/edit con items. Rutas admin. |
| V4-01 | Pendiente | Kit UI | Componentes reutilizables |
| V4-02 | Pendiente | Accesibilidad basica | Contraste y focus |

## Decisiones confirmadas
- Branding: tabla `settings` + assets en `/storage/app/public/branding`.
- Menu: DB (`menus`, `menu_items`) con jerarquia, permisos y orden.
- Catalogos: modelo generico (`catalogs`, `catalog_items`).
- Asistencia: agenda libre, sesiones definidas por docente.
- Periodos academicos: configurables por curso.

## Registro de aplicacion
- P0-01: Eliminado `courses.php`, unificado en `Course.php`. Actualizadas 20+ referencias en models, controllers, seeders, migrations. User model corregido.
- P0-02: Creada migracion `2026_05_31_000001_add_fields_to_modules_table.php`. Modelo Module actualizado.
- P1-01: Migraciones `class_sessions` y `attendance`. Modelos ClassSession, Attendance. AttendanceController (web + API). Vistas attendance/index, attendance/take. Rutas web/api.
- P1-02: Migracion perfiles academicos (`add_academic_fields_to_profiles_table`). UserController expandido (create/store/show/destroy). Vistas create, show. User model actualizado referencias.
- P1-03: ImportController, vista admin/import/index, rutas admin.
- P1-04: Migracion `academic_periods` + campos en courses. AcademicPeriod model + controller + vistas CRUD.
- P2-01: Migracion evaluation_attempts + campos max_attempts/time_limit/shuffle_questions. EvaluationAttempt model.
- P2-02: Migraciones question_bank + question_bank_options. QuestionBank/QuestionBankOption models.
- P2-03: GradebookController con vista y exportacion CSV. Ruta web.
- P3-01: ResourceController actualizado: store con validacion extensiones (jpg,png,pdf,doc,mp4,etc), storage en `storage/app/public/resources/`, soporte file_size/mime_type. Migracion `add_file_fields_to_resources_table`.
- P4-01: Migracion announcements. Announcement model. Admin\AnnouncementController. Vistas admin/announcements/{index,create,edit}. Rutas admin.
- P4-02: Migracion certificates. Certificate model. Admin\CertificateController. Vistas admin/certificates/{index,create}. Rutas admin.
- V3-01: Migraciones catalogs + catalog_items. Catalog/CatalogItem models. Admin\CatalogController. Vistas admin/catalogs/{index,edit}. Rutas admin.

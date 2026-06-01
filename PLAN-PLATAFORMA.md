# PLAN PLATAFORMA ACADEMICA (tipo Moodle)
Fecha de analisis: 2026-05-31  
Repositorio: EJMontes9/SistemaCapacitacion

## 1) Objetivo y alcance esperado
La plataforma debe soportar la gestion completa de cursos, alumnos y docentes, evaluaciones, contenido multimedia, asistencia y seguimiento academico con reportes.

Alcance funcional esperado:
1. Gestion de cursos: crear, editar, publicar, organizar por secciones y lecciones.
2. Gestion de usuarios: alta/edicion/baja de alumnos y docentes, roles, perfiles academicos.
3. Evaluaciones: banco de preguntas, intentos, calificacion y reportes.
4. Multimedia: carga de videos/documentos e integracion de URLs.
5. Asistencia: registro por sesion o leccion, reportes por alumno/curso.
6. Seguimiento: progreso, notas, analitica y alertas tempranas.

## 2) Estado actual (lo que ya existe)
La base ya cubre cursos, lecciones, evaluaciones y multimedia basico, mas progreso parcial.

Evidencia por modulo:
- Cursos, secciones, lecciones: rutas web y API, controladores y migraciones existentes.
- Matricula: tabla course_user y APIs de suscripcion.
- Progreso: lesson_user y endpoints de progreso por curso/alumno.
- Evaluaciones: evaluaciones, preguntas, opciones y resultados.
- Encuestas/ratings: surveys y survey_responses para satisfaccion y calificaciones de lecciones.
- Recursos multimedia: ResourceController admite archivo o URL y tipo (documento/imagen/url/video).
- Roles basicos: Admin/Instructor/Alumno via Spatie Permission.

## 3) Matriz de requisitos (cumple/parcial/no)
| Requisito | Estado | Evidencia principal |
|---|---|---|
| Gestion de cursos (CRUD, secciones, lecciones) | CUMPLE | routes\web.php, courseController.php, sectionsController.php, lessonsController.php, migrations courses/sections/lessons |
| Crear alumnos y docentes (roles, gestion) | PARCIAL | Admin\UserController.php (roles), seeders Roles.php, Spatie Permission en composer.json |
| Evaluaciones (preguntas, opciones, resultados) | CUMPLE | EvaluationController.php, models Evaluation/Question/Option/EvaluationResult |
| Multimedia (videos/documentos/urls) | CUMPLE | lessons (url/iframe), ResourceController.php, migrations lessons/resources |
| Asistencias | NO | No hay tablas/controladores para asistencia |
| Seguimiento de alumnos | PARCIAL | CourseUserController (progreso), estadisticas en courseController/EvaluationController |

## 4) Evidencia tecnica (archivos clave)
Rutas:
- routes\web.php, routes\api.php, routes\admin.php, routes\instructor.php

Controladores:
- app\Http\Controllers\courseController.php
- app\Http\Controllers\lessonsController.php
- app\Http\Controllers\sectionsController.php
- app\Http\Controllers\EvaluationController.php
- app\Http\Controllers\CourseUserController.php
- app\Http\Controllers\ResourceController.php
- app\Http\Controllers\SurveyController.php
- app\Http\Controllers\SurveyResponseController.php
- app\Http\Controllers\LessonRatingController.php

Modelos principales:
- app\Models\courses.php, section.php, lesson.php
- app\Models\Evaluation.php, Question.php, Option.php, EvaluationResult.php
- app\Models\Resource.php, Survey.php, SurveyResponse.php
- app\Models\CourseUser.php, User.php

Migraciones:
- database\migrations\*_create_courses_table.php
- database\migrations\*_create_sections_table.php
- database\migrations\*_create_lessons_table.php
- database\migrations\*_create_course_user_table.php
- database\migrations\*_create_lesson_user_table.php
- database\migrations\*_create_evaluations_table.php
- database\migrations\*_create_questions_table.php
- database\migrations\*_create_options_table.php
- database\migrations\*_create_evaluation_results_table.php
- database\migrations\*_create_surveys_table.php
- database\migrations\*_update_surveys_table.php
- database\migrations\*_survey_response.php
- database\migrations\*_update_survey_responses_table.php
- database\migrations\*_recreate_resources_table.php

## 5) Brechas y riesgos detectados
1. Asistencias inexistentes: no hay modelo/tabla ni UI para registro de asistencia.
2. Gestion completa de usuarios incompleta: hay roles, pero no CRUD de alumnos/docentes ni perfiles academicos.
3. Analitica academica limitada: hay progreso basico y notas por evaluacion, pero falta tablero y reportes integrales.
4. Inconsistencias tecnicas:
   - Nombres de modelos mezclados (Course vs courses). Hay referencias a App\Models\Course en algunos controladores.
   - Tabla modules sin campos, pero ModuleController espera name/description/completed.
   - ResourceSeeder usa type "pdf", pero la validacion del ResourceController acepta documento/imagen/url/video.
   - survey_responses evoluciono (response_text/response_number), revisar flujos de encuesta antiguos.
5. Falta de calendario, comunicacion y notificaciones.
6. Falta de certificaciones o constancias al finalizar.

## 5.5) Brechas y mejoras visuales/UX (institucion unica)
1. Identidad institucional no configurable: logo, nombre, colores, favicon y portada.
2. Colores y tipografias no centralizados en un tema reutilizable.
3. Menu actual quemado en vistas, no escalable ni configurable por roles.
4. Falta modulo de catalogos para listas maestras (categorias, niveles, modalidades, periodos, sedes, etc.).
5. Inconsistencia visual en formularios, tablas y dashboards (componentes repetidos).
6. Branding en emails/avisos y pantallas de autenticacion no alineado a la institucion.

## 6) Plan de mejora por fases (detalle completo)
### Fase 0: Correccion tecnica y normalizacion (corto plazo)
Objetivo: estabilizar base para crecer sin errores.
Entregables:
1. Unificar modelo Course/courses y referencias (migrar a un solo nombre).
2. Ajustar ModuleController o migracion modules para que coincidan campos.
3. Normalizar tipos de Resource y data de seeders.
4. Revisar y limpiar flujos de surveys/survey_responses.
5. Revisar relaciones y nombres en modelos (mayusculas/minusculas).
Criterio de salida: app compila, CRUD existente funciona sin errores de modelo.

### Fase 1: Nucleo academico completo
Objetivo: cubrir asistencia y gestion formal de alumnos/docentes.
Entregables:
1. Asistencia:
   - Tablas class_sessions (sesiones por curso/leccion) y attendance (estado por alumno).
   - Sesiones definidas libremente por el docente (dias de clase y dias sin asistencia).
   - UI para marcar asistencia por sesion y reportes por curso/alumno.
2. Gestion de usuarios:
   - CRUD admin para alumnos/docentes.
   - Perfil academico (cohorte, carrera, contacto, estado).
3. Matricula avanzada:
   - Importacion CSV y reglas de asignacion por curso.
4. Gestion academica configurable:
   - Periodos y frecuencia configurables (semanal, mensual, bimestral, trimestral, semestral).
   - Duracion y calendario por curso, con reglas de inicio/fin.
Criterio de salida: se puede crear alumno/docente, asignar curso y registrar asistencia.

### Fase 2: Evaluacion y seguimiento avanzado
Objetivo: convertir evaluaciones en sistema completo de calificacion.
Entregables:
1. Intentos de evaluacion, limite de tiempo y reintentos.
2. Banco de preguntas reutilizable por curso.
3. Libro de calificaciones (gradebook) por curso, por alumno y por seccion.
4. Reportes exportables (CSV/PDF) de notas.
Criterio de salida: instructor puede ver notas globales y exportarlas.

### Fase 3: Multimedia en servidor local
Objetivo: manejo robusto de contenido multimedia en el servidor.
Entregables:
1. Almacenamiento en disco local (/storage/uploads) con validacion y sanitizacion.
2. Control de tamanos maximos, cuotas por curso y control de acceso por matricula.
3. Compresion y optimizacion de imagenes, generacion de thumbnails.
4. Descarga directa y stream de video/audio con control de permisos.
Criterio de salida: carga/descarga/stream seguro en servidor local sin vulnerabilidades.

### Fase 4: Comunicacion, calendario y certificaciones
Objetivo: experiencia academica completa.
Entregables:
1. Calendario academico y eventos por curso.
2. Anuncios, mensajeria, notificaciones.
3. Certificados/constancias por finalizacion.
Criterio de salida: alumno recibe recordatorios y puede descargar certificado.

### Fase A: Identidad visual institucional
Objetivo: permitir personalizacion visual sin tocar codigo.
Entregables:
1. Panel de configuracion: nombre institucional, logo, favicon, colores primario/secundario, fondo login.
2. Variables de tema (CSS/Tailwind) aplicadas en layouts y componentes.
3. Vista previa y validaciones de assets (tamanos, formatos).
Criterio de salida: la institucion puede cambiar branding completo desde el panel.

### Fase B: Menu escalable y configurable
Objetivo: que el menu no sea quemado en codigo.
Entregables:
1. Estructura de menu en DB o archivo de configuracion (titulo, ruta, icono, orden, permisos).
2. Render del menu desde configuracion (navigation-menu.blade.php).
3. Soporte de submenus y activacion por rol.
Criterio de salida: se pueden agregar/ocultar items sin tocar vistas.

### Fase C: Modulo de catalogos
Objetivo: administrar listas maestras desde UI.
Entregables:
1. CRUD de catalogos (categorias, niveles, modalidades, periodos, sedes, estados).
2. Uso en formularios (cursos, usuarios y contenidos).
3. Orden, activacion/desactivacion y soft delete.
Criterio de salida: los catalogos se gestionan por admin y se reflejan en formularios.

### Fase D: Consistencia visual y accesibilidad
Objetivo: UI uniforme y profesional.
Entregables:
1. Kit de componentes (botones, tarjetas, tablas, badges, alerts).
2. Estados vacios, loading y validaciones consistentes.
3. Accesibilidad basica (contraste, focus visible, labels).
Criterio de salida: la interfaz mantiene consistencia y cumple accesibilidad basica.

## 7) Backlog priorizado (resumen)
| ID | Prioridad | Item | Descripcion breve | Dependencias |
|---|---|---|---|---|
| P0-01 | Alta | Unificar modelo Course | Estandarizar nombre y referencias en controllers/modelos | Ninguna |
| P0-02 | Alta | Ajuste modules | Sincronizar ModuleController con tabla modules | P0-01 |
| P1-01 | Alta | Asistencia | Tablas, API y UI para registro por sesion | P0-01 |
| P1-02 | Alta | CRUD alumnos/docentes | Admin UI + perfiles academicos | P0-01 |
| P1-03 | Media | Importacion CSV | Alta masiva de alumnos | P1-02 |
| P1-04 | Media | Periodos configurables | Semanal, bimestral, trimestral, semestral | P1-02 |
| P2-01 | Alta | Intentos evaluacion | Limites e historial de intentos | P0-01 |
| P2-02 | Media | Banco de preguntas | Reuso por curso | P2-01 |
| P2-03 | Media | Gradebook | Resumen de notas por curso/alumno | P2-01 |
| P3-01 | Media | Almacenamiento local | Validacion, compresion, cuotas y control de acceso | P0-01 |
| P4-01 | Baja | Calendario/avisos | Eventos y anuncios | P1-01 |
| P4-02 | Baja | Certificados | Emision por finalizacion | P2-03 |
| V1-01 | Alta | Panel de branding | Nombre, logo, favicon, colores, fondo login | Ninguna |
| V1-02 | Alta | Tema global | Variables CSS/Tailwind aplicadas en layouts | V1-01 |
| V2-01 | Alta | Menu configurable | Estructura en DB/config y render dinamico | Ninguna |
| V2-02 | Media | Submenus y permisos | Orden/roles para items de menu | V2-01 |
| V3-01 | Alta | Modulo de catalogos | CRUD de listas maestras | Ninguna |
| V4-01 | Media | Kit UI | Componentes reutilizables | V1-02 |
| V4-02 | Media | Accesibilidad basica | Contraste y focus | V4-01 |

## 8) Modelo de datos propuesto para asistencia (minimo)
Tablas sugeridas:
- class_sessions: id, course_id, section_id, lesson_id, session_date, duration, notes
- attendance: id, class_session_id, user_id, status(present/absent/late), comment
Notas:
- El docente crea sesiones manualmente (agenda libre). No se exige periodicidad fija.
- Solo se registra asistencia en sesiones creadas.

APIs sugeridas:
- POST /api/class-sessions
- POST /api/attendance
- GET /api/attendance/course/{courseId}

## 9) Metricas de seguimiento recomendadas
1. Progreso por alumno (% lecciones completadas por curso).
2. Promedio de notas por curso y por seccion.
3. Asistencia promedio por curso y alumno.
4. Alertas tempranas (bajo progreso + baja asistencia + baja nota).

## 10) Conclusion
El proyecto ya tiene una base funcional de cursos, lecciones, evaluaciones y multimedia, pero aun no cubre asistencia ni gestion completa de alumnos/docentes, ni analitica academica integral. Con las fases 0-4 se alcanza el nivel de plataforma academica completa tipo Moodle. Las fases A-D agregan personalizacion visual, menu escalable y catalogos para mejorar la reutilizabilidad sin necesidad de multi-tenant ni pagos.

## 11) Decisiones de implementacion (confirmadas)
| Tema | Decision |
|---|---|
| Branding | Guardar configuracion en tabla `settings` y assets en `/storage/app/public/branding`. |
| Menu | Estructura en DB (`menus`, `menu_items`) con jerarquia, permisos y orden. |
| Catalogos | Modelo generico (`catalogs`, `catalog_items`) para listas maestras. |
| Asistencia | Agenda libre: el docente define sesiones y dias sin clase. |
| Periodos academicos | Configurables por curso con reglas de recurrencia (semanal/bimestral/trimestral/semestral). |

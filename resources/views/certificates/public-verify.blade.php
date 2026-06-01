<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Certificado</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Verificación de Certificado</h3>
                    </div>
                    <div class="card-body">
                        @if(!$valid)
                            <div class="alert alert-danger text-center" role="alert">
                                <h4 class="alert-heading">Certificado no válido</h4>
                                <p>{{ $message }}</p>
                            </div>
                        @else
                            <div class="alert alert-success text-center" role="alert">
                                <h4 class="alert-heading">Certificado Válido</h4>
                                <p>Este certificado ha sido verificado exitosamente.</p>
                            </div>

                            @if($certificate->body_html)
                                <div class="mt-4 p-3 bg-white border rounded" style="max-height: 600px; overflow: auto;">
                                    <div style="transform: scale(0.6); transform-origin: top left; width: 166%;">
                                        {!! $certificate->body_html !!}
                                    </div>
                                </div>
                            @else
                                <div class="text-center mt-4">
                                    <h5>{{ $certificate->user->name }}</h5>
                                    <p class="lead">Completó el curso</p>
                                    <h4 class="text-primary">{{ $certificate->course->title }}</h4>
                                    <p>Fecha de finalización: <strong>{{ $certificate->completion_date->format('d/m/Y') }}</strong></p>
                                    @if($certificate->grade)
                                    <p>Calificación: <strong>{{ $certificate->grade }}</strong></p>
                                    @endif
                                    <p class="text-muted">
                                        <small>Código: {{ $certificate->certificate_code }}</small>
                                    </p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-3">
            <div class="col-md-8">
                <form method="GET" action="{{ url('/certificates/verify') }}">
                    <div class="input-group">
                        <input type="text" name="code" class="form-control" placeholder="Ingrese código de certificado" required>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Verificar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

@extends('adminlte::page')

@section('title', 'Configuración de Marca')

@section('content_header')
    <h1 class="font-weight-bold">Configuración de Marca</h1>
    <ol class="breadcrumb mt-2 mb-0" style="background: transparent; padding: 0;">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Branding</li>
    </ol>
@stop

@section('content')
    @if (session('info'))
        <div class="alert alert-success">
            <strong>{{ session('info') }}</strong>
        </div>
    @endif

    <div class="row" x-data="brandingPreview()">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Personalizar Marca</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.branding.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="site_name">Nombre del Sitio</label>
                            <input type="text" class="form-control @error('site_name') is-invalid @enderror" id="site_name" name="site_name"
                                value="{{ old('site_name', $settings['site_name']->value ?? 'StudyApp') }}" required
                                x-model="siteName">
                            @error('site_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group">
                            <label for="site_description">Descripción del Sitio</label>
                            <textarea class="form-control @error('site_description') is-invalid @enderror" id="site_description" name="site_description"
                                rows="3" x-model="siteDescription">{{ old('site_description', $settings['site_description']->value ?? '') }}</textarea>
                            @error('site_description') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="primary_color">Color Primario</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" :style="'background-color: ' + primaryColor + '; width: 38px;'" style="background-color: {{ old('primary_color', $settings['primary_color']->value ?? '#3B82F6') }}; width: 38px;"></span>
                                        </div>
                                        <input type="color" class="form-control @error('primary_color') is-invalid @enderror" id="primary_color" name="primary_color"
                                            value="{{ old('primary_color', $settings['primary_color']->value ?? '#3B82F6') }}" required
                                            x-model="primaryColor">
                                        @error('primary_color') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="secondary_color">Color Secundario</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" :style="'background-color: ' + secondaryColor + '; width: 38px;'" style="background-color: {{ old('secondary_color', $settings['secondary_color']->value ?? '#1E40AF') }}; width: 38px;"></span>
                                        </div>
                                        <input type="color" class="form-control @error('secondary_color') is-invalid @enderror" id="secondary_color" name="secondary_color"
                                            value="{{ old('secondary_color', $settings['secondary_color']->value ?? '#1E40AF') }}" required
                                            x-model="secondaryColor">
                                        @error('secondary_color') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="site_logo">Logo del Sitio</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('site_logo') is-invalid @enderror" id="site_logo" name="site_logo" accept="image/*"
                                        @change="previewLogo($event)">
                                    <label class="custom-file-label" for="site_logo">Seleccionar archivo</label>
                                </div>
                            </div>
                            @if(isset($settings['site_logo']) && $settings['site_logo']->value)
                                <div class="mt-2" x-show="!logoPreview">
                                    <img src="{{ asset('storage/' . $settings['site_logo']->value) }}" alt="Logo" style="max-height: 60px;">
                                </div>
                            @endif
                            <div class="mt-2" x-show="logoPreview" x-cloak>
                                <img :src="logoPreview" alt="Logo Preview" style="max-height: 60px;">
                            </div>
                            @error('site_logo') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                            <small class="form-text text-muted">Formatos: PNG, JPG, SVG, WEBP. Máx 2MB.</small>
                        </div>

                        <div class="form-group">
                            <label for="site_favicon">Favicon</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('site_favicon') is-invalid @enderror" id="site_favicon" name="site_favicon" accept="image/*"
                                        @change="previewFavicon($event)">
                                    <label class="custom-file-label" for="site_favicon">Seleccionar archivo</label>
                                </div>
                            </div>
                            @if(isset($settings['site_favicon']) && $settings['site_favicon']->value)
                                <div class="mt-2" x-show="!faviconPreview">
                                    <img src="{{ asset('storage/' . $settings['site_favicon']->value) }}" alt="Favicon" style="max-height: 32px;">
                                </div>
                            @endif
                            <div class="mt-2" x-show="faviconPreview" x-cloak>
                                <img :src="faviconPreview" alt="Favicon Preview" style="max-height: 32px;">
                            </div>
                            @error('site_favicon') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                            <small class="form-text text-muted">Formatos: PNG, ICO. Máx 1MB.</small>
                        </div>

                        <div class="form-group">
                            <label for="login_bg">Fondo de Login</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('login_bg') is-invalid @enderror" id="login_bg" name="login_bg" accept="image/*"
                                        @change="previewLoginBg($event)">
                                    <label class="custom-file-label" for="login_bg">Seleccionar archivo</label>
                                </div>
                            </div>
                            @if(isset($settings['login_bg']) && $settings['login_bg']->value)
                                <div class="mt-2" x-show="!loginBgPreview">
                                    <img src="{{ asset('storage/' . $settings['login_bg']->value) }}" alt="Login BG" style="max-height: 100px; width: auto;">
                                </div>
                            @endif
                            <div class="mt-2" x-show="loginBgPreview" x-cloak>
                                <img :src="loginBgPreview" alt="Login BG Preview" style="max-height: 100px; width: auto;">
                            </div>
                            @error('login_bg') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                            <small class="form-text text-muted">Formatos: PNG, JPG, WEBP. Máx 5MB.</small>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            <div>
                                <button type="button" onclick="if(confirm('¿Restablecer valores predeterminados?')){ document.getElementById('resetForm').submit(); }" class="btn btn-danger">Restablecer Valores</button>
                                <button type="button" onclick="window.location='{{ route('admin.home') }}'" class="btn btn-secondary">Cancelar</button>
                            </div>
                        </div>
                    </form>
                    <form id="resetForm" action="{{ route('admin.branding.reset') }}" method="POST" style="display:none;">@csrf</form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Vista Previa</h3>
                </div>
                <div class="card-body text-center">
                    <h5 :style="'color: ' + primaryColor">
                        <span x-text="siteName || 'StudyApp'"></span>
                    </h5>
                    <template x-if="logoPreview">
                        <img :src="logoPreview" alt="Logo Preview" style="max-height: 80px;" class="mb-3">
                    </template>
                    <template x-if="!logoPreview && '{{ isset($settings['site_logo']) && $settings['site_logo']->value ? 'true' : 'false' }}' === 'true'">
                        <img src="{{ isset($settings['site_logo']) && $settings['site_logo']->value ? asset('storage/' . $settings['site_logo']->value) : '' }}" alt="Logo Preview" style="max-height: 80px;" class="mb-3">
                    </template>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="badge" :style="'background-color: ' + primaryColor + '; color: white;'">Primario</span>
                        <span class="badge" :style="'background-color: ' + secondaryColor + '; color: white;'">Secundario</span>
                    </div>
                    <p class="text-muted small" x-text="siteDescription || 'Sin descripción'"></p>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>[x-cloak] { display: none !important; }</style>
@stop

@section('js')
    <script>
        function brandingPreview() {
            return {
                siteName: '{{ old('site_name', $settings['site_name']->value ?? 'StudyApp') }}',
                siteDescription: '{{ old('site_description', $settings['site_description']->value ?? '') }}',
                primaryColor: '{{ old('primary_color', $settings['primary_color']->value ?? '#3B82F6') }}',
                secondaryColor: '{{ old('secondary_color', $settings['secondary_color']->value ?? '#1E40AF') }}',
                logoPreview: null,
                faviconPreview: null,
                loginBgPreview: null,
                previewLogo(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => this.logoPreview = e.target.result;
                        reader.readAsDataURL(file);
                    }
                },
                previewFavicon(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => this.faviconPreview = e.target.result;
                        reader.readAsDataURL(file);
                    }
                },
                previewLoginBg(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => this.loginBgPreview = e.target.result;
                        reader.readAsDataURL(file);
                    }
                }
            }
        }

        $(document).ready(function () {
            $('.custom-file-input').on('change', function () {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });

            window.setTimeout(function () {
                $(".alert").fadeTo(500, 0).slideUp(500, function () {
                    $(this).remove();
                });
            }, 5000);
        });
    </script>
@stop

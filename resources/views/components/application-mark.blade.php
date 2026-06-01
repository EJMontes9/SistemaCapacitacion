@php
    $siteLogo = \App\Models\Setting::getValue('site_logo');
    $siteName = \App\Models\Setting::getValue('site_name', 'StudyApp');
    $primaryColor = \App\Models\Setting::getValue('primary_color', '#3B82F6');
@endphp
@if($siteLogo)
    <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" style="max-height: 40px; width: auto;">
@else
    <div style="width: 40px; height: 40px; background: {{ $primaryColor }}; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
        <span style="color: white; font-size: 18px; font-weight: bold;">{{ substr($siteName, 0, 1) }}</span>
    </div>
@endif

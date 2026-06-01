@php
    $siteLogo = \App\Models\Setting::getValue('site_logo');
    $siteName = \App\Models\Setting::getValue('site_name', 'StudyApp');
    $primaryColor = \App\Models\Setting::getValue('primary_color', '#3B82F6');
@endphp
<a href="/" class="flex flex-col items-center gap-2 no-underline">
    @if($siteLogo)
        <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" style="max-height: 80px; width: auto;">
    @else
        <div style="width: 80px; height: 80px; background: {{ $primaryColor }}; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
            <span style="color: white; font-size: 32px; font-weight: bold;">{{ substr($siteName, 0, 1) }}</span>
        </div>
    @endif
    <span style="color: {{ $primaryColor }}; font-size: 1.25rem; font-weight: 600;">{{ $siteName }}</span>
</a>

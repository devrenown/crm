<!-- Logo -->
@php
  $theme = app(\App\Settings\ThemeSettings::class);
@endphp
<div class="header-left">
    {{-- Theme('logo_light') --}}
    @if ($theme->color_scheme == 'maroon' || $theme->color_scheme == 'light')
    <a href="{{ route('dashboard') }}" class="logo2">
        <img src="{{ Theme('logo_dark') ? asset('storage/' . Theme('logo_dark')) : asset('images/logo2.png') }}" style="height: 40px; width: auto;" alt="Logo">
    </a>
    @else
    <a href="{{ route('dashboard') }}" class="logo">
        <img src="{{ Theme('logo_light') ? asset('storage/' . Theme('logo_light')) : asset('images/logo.png') }}" style="height: 40px; width: auto;" alt="Logo">
    </a>
    @endif
</div>
<!-- /Logo -->

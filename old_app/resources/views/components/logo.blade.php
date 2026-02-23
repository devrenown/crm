<!-- Logo -->
<div class="header-left">
    <a href="{{ route('dashboard') }}" class="logo">
        <img src="{{ Theme('logo_dark') ? asset('storage/settings/theme/' . Theme('logo_light')) : asset('images/logo.png') }}" style="height: 40px; width: auto;" alt="Logo">
    </a>
    <a href="{{ route('dashboard') }}" class="logo2">
        <img src="{{ Theme('logo_dark') ? asset('storage/settings/theme/' . Theme('logo_dark')) : asset('images/logo2.png') }}" style="height: 40px; width: auto;" alt="Logo">
    </a>
</div>
<!-- /Logo -->

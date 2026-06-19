<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Maintenance — {{ $profile?->user?->name ?? config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    @vite(['resources/css/app.css'])
</head>
<body>

<div class="maintenance">
    <p class="maintenance__eyebrow">503</p>
    <h1 class="maintenance__heading">Under maintenance<em>Back soon.</em></h1>
    <p class="maintenance__message">
        {{ $profile?->tagline ?? 'The site is temporarily down for maintenance. Check back in a little while.' }}
    </p>
    <div class="maintenance__meta">
        @if ($profile?->email_public)
            <span>Need something urgent? <a href="mailto:{{ $profile->email_public }}">{{ $profile->email_public }}</a></span>
        @endif
    </div>
</div>

</body>
</html>

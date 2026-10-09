<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">

    <title>Maintenance — {{ $profile?->user?->name ?? config('app.name') }}</title>

    {{-- No Alpine on this page: apply the saved theme before paint. --}}
    <script>
        try { if (localStorage.getItem('dark') === 'true') document.documentElement.classList.add('dark'); } catch (e) {}
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=dm-serif-display:400,400i|dm-sans:400,400i,500|dm-mono:400,500&display=swap">
    @vite(['resources/css/app.css'])
</head>
<body>

@php
    $firstName = Str::before($profile?->user?->nickname ?? config('app.name'), ' ');
    $shell     = 'max-w-page mx-auto px-gutter';
@endphp

<div class="min-h-svh flex flex-col">

    {{-- Same mark and height as the main nav, so the page feels like the site --}}
    <header class="border-b border-border">
        <div class="{{ $shell }} h-[var(--nav-h)] flex items-center">
            <span class="font-serif text-lg leading-none text-text tracking-[-0.01em]">
                {{ $firstName }}<span class="text-accent">.</span>
            </span>
        </div>
    </header>

    <main class="{{ $shell }} w-full flex-1 flex items-center py-section">
        <div>
            <p class="eyebrow eyebrow--rule mb-6">503 · Maintenance</p>

            <h1 class="h-display max-w-[19ch]">
                Under maintenance.<br>
                <em class="italic text-accent">Back soon.</em>
            </h1>

            {{-- <p class="mt-6 text-md text-text-secondary max-w-measure">
                {{ 'The site is temporarily down for maintenance. Check back in a little while.' }}
            </p> --}}

            @if ($profile?->email_public)
                <dl class="mt-block border-t border-border max-w-[40rem]">
                    <div class="grid md:grid-cols-[var(--col-meta)_1fr] gap-x-12 gap-y-1 py-5 border-b border-border">
                        <dt class="eyebrow md:pt-1">Urgent?</dt>
                        <dd>
                            <a href="mailto:{{ $profile->email_public }}" class="link text-base break-all">{{ $profile->email_public }}</a>
                        </dd>
                    </div>
                </dl>
            @endif
        </div>
    </main>

    <footer class="{{ $shell }} w-full py-8 border-t border-border font-mono text-xs text-text-muted">
        <p>© {{ date('Y') }} {{ $profile?->user?->name ?? config('app.name') }}</p>
    </footer>

</div>

</body>
</html>
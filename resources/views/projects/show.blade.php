{{--
    Project detail page — resources/views/projects/show.blade.php

    Expects:  $project            (with technologies.technologyField + images loaded)
              $profile            (same as the home page; may be null)
              $previousProject    (optional, null at the ends)
              $nextProject        (optional, null at the ends)
--}}
<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="siteData()"
    x-bind:class="{ 'dark': dark }"
>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $images = $project->images;
        $cover  = $images->firstWhere('is_cover', true) ?? $images->first();
    @endphp

    <title>{{ $project->name }} — {{ config('app.name') }}</title>
    @if ($project->short_description)
        <meta name="description" content="{{ Str::limit($project->short_description, 160) }}">
    @endif
    <meta property="og:title" content="{{ $project->name }}">
    @if ($cover)
        <meta property="og:image" content="{{ url(Storage::url($cover->image)) }}">
    @endif

    <script>
        document.documentElement.classList.add('js');
        try { if (localStorage.getItem('dark') === 'true') document.documentElement.classList.add('dark'); } catch (e) {}
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=dm-serif-display:400,400i|dm-sans:400,400i,500|dm-mono:400,500&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

@php
    $profile ??= null;
    $previousProject ??= null;
    $nextProject ??= null;

    $tagStyle = function (\App\Models\Technology $tech): string {
        $hex = $tech->technologyField?->color ?? '';
        return preg_match('/^#(?:[0-9a-fA-F]{3}){1,2}$/', $hex) ? "--tag:{$hex};" : '';
    };
    $firstName = Str::before($profile?->user?->nickname ?? config('app.name'), ' ');

    $gutter = 'px-gutter';
    $shell  = "max-w-page mx-auto {$gutter}";
    $home   = url('/');

    $statusClass = match ($project->status) {
        'completed'   => 'badge--accent',
        'in_progress' => 'badge--warn',
        default       => '',
    };
    $statusLabel = match ($project->status) {
        'in_progress' => 'in progress',
        'completed'   => 'completed',
        'archived'    => 'archived',
        default       => $project->status,
    };

    /* Lightbox order: cover first, then the rest in their admin order. */
    $gallery = $images->reject(fn ($img) => $cover && $img->is($cover))->values();
    $lightboxItems = collect([$cover])->filter()->merge($gallery)->values()
        ->map(fn ($img, $i) => [
            'src'     => Storage::url($img->image),
            'alt'     => $img->caption ?: "{$project->name}, image " . ($i + 1),
            'caption' => $img->caption,
        ]);
    $galleryOffset = $cover ? 1 : 0;   // index of the first gallery image in the lightbox
@endphp

<a href="#main" class="skip-link">Skip to content</a>

{{-- ── HEADER ───────────────────────────────────────── --}}
<header class="fixed inset-x-0 top-0 z-100 bg-[var(--nav-bg)] backdrop-blur-md border-b border-border">
    <nav aria-label="Primary" class="{{ $shell }} h-[var(--nav-h)] flex items-center gap-6">
        <a href="{{ $home }}" class="font-serif text-lg leading-none text-text tracking-[-0.01em] shrink-0">
            {{ $firstName }}<span class="text-accent">.</span>
        </a>

        <a href="{{ $home }}#projects" class="nav-link ml-auto gap-2">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
            All projects
        </a>

        <button
            type="button"
            class="icon-btn shrink-0"
            @click="dark = !dark"
            :aria-label="dark ? 'Switch to light mode' : 'Switch to dark mode'"
        >
            <svg x-show="!dark" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="4.5"/>
                <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
            </svg>
            <svg x-show="dark" x-cloak aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
            </svg>
        </button>
    </nav>
</header>

<main id="main" tabindex="-1" class="focus:outline-none" x-data="gallery(@js($lightboxItems))" @keydown.window="onKey($event)">

{{-- ── INTRO ────────────────────────────────────────── --}}
<section aria-labelledby="project-title" class="{{ $shell }} pt-[calc(var(--nav-h)+4rem)] md:pt-[calc(var(--nav-h)+6rem)] pb-12">
    <p class="reveal eyebrow eyebrow--rule mb-6">
        Project
        @if ($project->is_featured)
            <span aria-hidden="true">·</span> Featured
        @endif
    </p>

    <h1 id="project-title" class="reveal font-serif text-2xl text-text tracking-[-0.025em] max-w-[18ch]" style="transition-delay: 80ms">
        {{ $project->name }}<span class="text-accent">.</span>
    </h1>

    @if ($project->short_description)
        <p class="reveal mt-6 text-md md:text-lg md:leading-[1.45] text-text max-w-[40ch]" style="transition-delay: 160ms">{{ $project->short_description }}</p>
    @endif

    @if ($project->demo_url || $project->repository_url)
        <div class="reveal mt-8 flex flex-wrap items-center gap-3" style="transition-delay: 240ms">
            @if ($project->demo_url)
                <a href="{{ $project->demo_url }}" target="_blank" rel="noopener noreferrer" class="btn btn--primary">
                    Visit live site<span class="sr-only"> (opens in a new tab)</span>
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7"/><path d="M8 7h9v9"/></svg>
                </a>
            @endif
            @if ($project->repository_url)
                <a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer" class="btn {{ $project->demo_url ? 'btn--secondary' : 'btn--primary' }}">
                    View source<span class="sr-only"> (opens in a new tab)</span>
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7"/><path d="M8 7h9v9"/></svg>
                </a>
            @endif
        </div>
    @endif
</section>

{{-- ── FACTS ────────────────────────────────────────── --}}
{{-- Same label/value grid as the home page's Experience and Contact. --}}
<section aria-label="Project details" class="{{ $shell }} pt-block">
    <dl class="reveal border-t border-border">
        <div class="grid md:grid-cols-[var(--col-meta)_1fr] gap-x-12 gap-y-2 py-5 border-b border-border">
            <dt class="eyebrow md:pt-1">Status</dt>
            <dd><span class="badge {{ $statusClass }}">{{ $statusLabel }}</span></dd>
        </div>

        @if ($project->technologies->count())
            <div class="grid md:grid-cols-[var(--col-meta)_1fr] gap-x-12 gap-y-2 py-5 border-b border-border">
                <dt class="eyebrow md:pt-1">Stack</dt>
                <dd>
                    <ul class="flex flex-wrap gap-2" aria-label="Technologies">
                        @foreach ($project->technologies as $tech)
                            <li class="tag" style="{{ $tagStyle($tech) }}"><span class="tag__dot" aria-hidden="true"></span>{{ $tech->name }}</li>
                        @endforeach
                    </ul>
                </dd>
            </div>
        @endif

        @if ($project->demo_url || $project->repository_url)
            <div class="grid md:grid-cols-[var(--col-meta)_1fr] gap-x-12 gap-y-2 py-5 border-b border-border">
                <dt class="eyebrow md:pt-1">Links</dt>
                <dd class="flex flex-wrap gap-x-6">
                    @if ($project->demo_url)
                        <a href="{{ $project->demo_url }}" target="_blank" rel="noopener noreferrer" class="link text-base inline-flex items-center min-h-11 md:min-h-0 break-all">{{ Str::of($project->demo_url)->after('://')->rtrim('/') }}<span class="sr-only"> (opens in a new tab)</span></a>
                    @endif
                    @if ($project->repository_url)
                        <a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer" class="link text-base inline-flex items-center min-h-11 md:min-h-0">Source code<span class="sr-only"> (opens in a new tab)</span></a>
                    @endif
                </dd>
            </div>
        @endif
    </dl>
</section>

{{-- ── OVERVIEW ─────────────────────────────────────── --}}
@if ($project->description)
    <section aria-labelledby="overview-title" class="{{ $shell }} pt-section">
        <div class="reveal grid md:grid-cols-[var(--col-meta)_1fr] gap-x-12 gap-y-4">
            <h2 id="overview-title" class="eyebrow md:pt-1.5 self-start">Overview</h2>
            {{-- Markdown with raw HTML stripped: plain text from a Textarea
                 becomes paragraphs; a MarkdownEditor gets full formatting.
                 Using Filament's RichEditor instead? Swap this line for
                 {!! str($project->description)->sanitizeHtml() !!} --}}
            <div class="prose-body">
                {!! Str::markdown($project->description, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
            </div>
        </div>
    </section>
@endif

{{-- ── GALLERY ──────────────────────────────────────── --}}
@if ($gallery->count())
    <section aria-labelledby="gallery-title" class="{{ $shell }} pt-section">
        <div class="reveal section-head">
            <h2 id="gallery-title" class="h-section">Gallery</h2>
            <p class="font-mono text-xs text-text-muted">{{ $gallery->count() }} {{ Str::plural('image', $gallery->count()) }}</p>
        </div>

        {{-- Two columns; a lone or odd-last image spans the full width so
             the grid never ends on a half-empty row. --}}
        <div class="stagger grid md:grid-cols-2 gap-x-6 gap-y-10">
            @foreach ($gallery as $img)
                <figure @class(['md:col-span-2' => $loop->last && $loop->count % 2 === 1])>
                    <button
                        type="button"
                        @class(['shot', 'aspect-[16/9]' => $loop->last && $loop->count % 2 === 1, 'aspect-[4/3]' => !($loop->last && $loop->count % 2 === 1)])
                        @click="show({{ $loop->index + $galleryOffset }})"
                        aria-label="Open image {{ $loop->index + $galleryOffset + 1 }} of {{ $lightboxItems->count() }}{{ $img->caption ? ': ' . $img->caption : '' }}"
                    >
                        <img src="{{ Storage::url($img->image) }}" alt="{{ $img->caption ?: $project->name . ', image ' . ($loop->index + $galleryOffset + 1) }}" loading="lazy" decoding="async" data-fit>
                    </button>
                    @if ($img->caption)
                        <figcaption class="mt-3 text-sm text-text-muted max-w-measure">{{ $img->caption }}</figcaption>
                    @endif
                </figure>
            @endforeach
        </div>
    </section>
@endif

{{-- ── PREVIOUS / NEXT ──────────────────────────────── --}}
@if ($previousProject || $nextProject)
    <nav aria-label="More projects" class="{{ $shell }} pt-section">
        <div class="grid grid-cols-2 gap-6 border-t border-border">
            <div>
                @if ($previousProject)
                    <a href="{{ route('projects.show', $previousProject) }}" class="group flex flex-col gap-1 py-6">
                        <span class="eyebrow">← Previous</span>
                        <span class="h-card group-hover:underline decoration-[color:var(--border-strong)] underline-offset-[0.2em]">{{ $previousProject->name }}</span>
                    </a>
                @endif
            </div>
            <div class="text-right">
                @if ($nextProject)
                    <a href="{{ route('projects.show', $nextProject) }}" class="group flex flex-col items-end gap-1 py-6">
                        <span class="eyebrow">Next →</span>
                        <span class="h-card group-hover:underline decoration-[color:var(--border-strong)] underline-offset-[0.2em]">{{ $nextProject->name }}</span>
                    </a>
                @endif
            </div>
        </div>
    </nav>
@endif

{{-- ── CLOSING CTA ──────────────────────────────────── --}}
<section aria-labelledby="cta-title" class="{{ $shell }} py-section">
    <div class="reveal border-t border-border pt-block">
        {{-- PLACEHOLDER COPY — edit freely. --}}
        <h2 id="cta-title" class="font-serif text-xl text-text tracking-[-0.02em] max-w-[22ch]">
            Working on something similar? <em class="italic text-accent">Let's talk.</em>
        </h2>
        <div class="mt-8 flex flex-wrap items-center gap-3">
            <a href="{{ $home }}#contact" class="btn btn--primary">
                Get in touch
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
            <a href="{{ $home }}#projects" class="btn btn--ghost">Back to all projects</a>
        </div>
    </div>
</section>

{{-- ── LIGHTBOX ─────────────────────────────────────── --}}
@if ($lightboxItems->count())
    <div
        x-ref="lightbox"
        x-show="open"
        x-transition.opacity.duration.200ms
        x-cloak
        role="dialog"
        aria-modal="true"
        aria-label="Image viewer"
        class="fixed inset-0 z-200 flex flex-col bg-bg"
        @keydown.tab="trapFocus($event)"
        @touchstart.passive="touchStart($event)"
        @touchend="touchEnd($event)"
    >
        <div class="{{ $gutter }} h-[var(--nav-h)] shrink-0 flex items-center justify-between gap-4 border-b border-border">
            <p class="font-mono text-xs text-text-muted tabular-nums" aria-live="polite" x-text="counter"></p>
            <button type="button" x-ref="lightboxClose" class="icon-btn" @click="open = false" aria-label="Close image viewer">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="relative flex-1 min-h-0 flex items-center justify-center p-4 md:px-20 md:py-8" @click.self="open = false">
            <img :src="current.src" :alt="current.alt" class="max-w-full max-h-full object-contain rounded-sm border border-border bg-bg-alt">

            @if ($lightboxItems->count() > 1)
                <button type="button" class="icon-btn absolute left-4 top-1/2 -translate-y-1/2 bg-[var(--nav-bg)] hidden md:inline-flex" @click="prev()" aria-label="Previous image">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <button type="button" class="icon-btn absolute right-4 top-1/2 -translate-y-1/2 bg-[var(--nav-bg)] hidden md:inline-flex" @click="next()" aria-label="Next image">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            @endif
        </div>

        <div class="{{ $gutter }} shrink-0 min-h-16 py-4 flex items-center justify-between gap-4 border-t border-border">
            <p class="text-sm text-text-secondary max-w-measure" x-text="current.caption || ''"></p>
            @if ($lightboxItems->count() > 1)
                {{-- On phones the arrows live here, in thumb reach (swipe also works). --}}
                <div class="flex gap-2 md:hidden shrink-0">
                    <button type="button" class="icon-btn" @click="prev()" aria-label="Previous image">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    </button>
                    <button type="button" class="icon-btn" @click="next()" aria-label="Next image">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                    </button>
                </div>
            @endif
        </div>
    </div>
@endif

</main>

{{-- ── FOOTER ───────────────────────────────────────── --}}
<footer class="{{ $shell }} py-8 border-t border-border flex flex-col gap-3 md:flex-row md:items-center md:justify-between font-mono text-xs text-text-muted">
    <div class="flex items-center gap-3" x-data="clock()">
        <span class="inline-flex items-center gap-2 text-text-secondary">
            <span class="status-dot" aria-hidden="true"></span>
            {{ 'Available' }}
        </span>
        <span aria-hidden="true">·</span>
        <time x-text="currentTime" class="tabular-nums"></time>
    </div>
    <p>© {{ date('Y') }} {{ $profile?->user?->name ?? config('app.name') }}</p>
</footer>

</body>
</html>
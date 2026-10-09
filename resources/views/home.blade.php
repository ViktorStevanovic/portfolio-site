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

    @if ($profile)
        <title>{{ config('app.name') }}</title>
        <meta name="description" content="{{ $profile->tagline ?? Str::limit($profile->bio, 160) }}">
    @else
        <title>{{ config('app.name') }}</title>
    @endif

    {{-- Runs before first paint: applies the saved theme (no light flash for
         dark-mode visitors) and marks JS as available so .reveal content is
         only hidden when something will reveal it. Mirrors siteData()'s logic. --}}
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
    /* Tags now render a neutral pill + a dot. Only the hue comes from the
       DB; CSS normalizes lightness/chroma per theme. The hex is validated
       because it is written into a style attribute. */
    $tagStyle = function (\App\Models\Technology $tech): string {
        $hex = $tech->technologyField?->color ?? '';
        return preg_match('/^#(?:[0-9a-fA-F]{3}){1,2}$/', $hex) ? "--tag:{$hex};" : '';
    };
    $firstName = Str::before($profile?->user?->nickname ?? config('app.name'), ' ');

    $fullName  = trim((string) ($profile?->user?->name ?? ''));
    $nameFirst = Str::before($fullName, ' ') ?: $firstName;
    $nameRest  = Str::contains($fullName, ' ') ? Str::after($fullName, ' ') : '';

    /* One shell for nav, sections and footer = one optical edge. */
    $gutter = 'px-gutter';
    $shell  = "max-w-page mx-auto {$gutter}";

    $navItems = array_filter([
        'about'      => 'Home',
        'experience' => $experiences->count() ? 'Experience' : null,
        'projects'   => $projects->count() ? 'Projects' : null,
        'contact'    => 'Contact',
    ]);
@endphp

<a href="#main" class="skip-link">Skip to content</a>

{{-- ── HEADER / NAV ─────────────────────────────────── --}}
<header
    class="fixed inset-x-0 top-0 z-100 bg-[var(--nav-bg)] backdrop-blur-md border-b border-border"
    @keydown.escape.window="menuOpen = false"
>
    <nav aria-label="Primary" class="{{ $shell }} h-[var(--nav-h)] flex items-center gap-8">
        <a href="#about" @click.prevent="scrollTo('about')" class="font-serif text-lg leading-none text-text tracking-[-0.01em] shrink-0">
            {{ $firstName }}<span class="text-accent">.</span>
        </a>

        <ul class="hidden md:flex items-center gap-8 ml-auto">
            @foreach ($navItems as $id => $label)
                <li>
                    <a href="#{{ $id }}"
                       @click.prevent="scrollTo('{{ $id }}')"
                       :class="activeSection === '{{ $id }}' && 'active'"
                       :aria-current="activeSection === '{{ $id }}' && 'location'"
                       class="nav-link">{{ $label }}</a>
                </li>
            @endforeach
        </ul>

        <div class="flex items-center gap-2 shrink-0 ml-auto md:ml-0">
            <button
                type="button"
                class="icon-btn"
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

            <button
                type="button"
                class="icon-btn md:hidden"
                @click="menuOpen = !menuOpen"
                :aria-expanded="menuOpen.toString()"
                aria-controls="mobile-menu"
                :aria-label="menuOpen ? 'Close menu' : 'Open menu'"
            >
                <svg x-show="!menuOpen" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"><path d="M4 8h16M4 16h16"/></svg>
                <svg x-show="menuOpen" x-cloak aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
        </div>
    </nav>

    <div
        id="mobile-menu"
        class="md:hidden {{ $gutter }} pb-4 border-t border-border bg-bg"
        x-show="menuOpen"
        x-transition.opacity
        x-cloak
    >
        @foreach ($navItems as $id => $label)
            <a href="#{{ $id }}"
               @click.prevent="scrollTo('{{ $id }}'); menuOpen = false"
               :class="activeSection === '{{ $id }}' && 'active'"
               class="menu-link">{{ $label }}</a>
        @endforeach
    </div>
</header>

<main id="main" tabindex="-1" class="focus:outline-none">

{{-- ── HERO ─────────────────────────────────────────── --}}
{{-- Left-aligned to share the edge every section below uses. --}}
<section id="about" aria-labelledby="hero-title" class="section {{ $shell }} flex items-center min-h-[min(100svh,60rem)] pt-[calc(var(--nav-h)+4rem)] pb-section">
    @if ($profile)
        <div class="w-full">
            {{-- OPTIONAL: needs an `avatar` field on Profile (see next steps). --}}
            @if (!empty($profile->avatar))
                <img src="{{ Storage::url($profile->avatar) }}" alt="" width="72" height="72"
                     class="reveal size-18 rounded-full object-cover border border-border mb-8">
            @endif

            <p class="reveal eyebrow eyebrow--rule mb-6">{{ $profile->job_title ?? 'Developer' }}</p>

            <h1 id="hero-title" class="reveal h-display max-w-[14ch]" style="transition-delay: 80ms">
                {{ $nameFirst }}@if ($nameRest) <em class="italic">{{ $nameRest }}</em>@endif<span class="text-accent">.</span>
            </h1>

            @if ($profile->tagline)
                <p class="reveal mt-6 text-md md:text-lg md:leading-[1.45] text-text max-w-[36ch]" style="transition-delay: 160ms">{{ $profile->tagline }}</p>
            @endif

            @if ($profile->bio)
                <p class="reveal mt-stack-md text-base text-text-secondary max-w-measure" style="transition-delay: 220ms">{{ $profile->bio }}</p>
            @endif

            <div class="reveal mt-10 flex flex-wrap items-center gap-3" style="transition-delay: 280ms">
                <button type="button" class="btn btn--primary" @click="$dispatch('open-contact')">
                    Get in touch
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </button>
                @if ($profile->cv_path)
                    <a href="{{ route('cv.download') }}" class="btn btn--secondary">
                        Download résumé
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg>
                    </a>
                @endif
            </div>
        </div>
    @else
        <p class="eyebrow eyebrow--rule">Portfolio coming soon.</p>
    @endif
</section>

{{-- ── EXPERIENCE ───────────────────────────────────── --}}
@if ($experiences->count())
<section id="experience" aria-labelledby="experience-title" class="section {{ $shell }} py-section border-t border-border">
    <div class="reveal section-head">
        <h2 id="experience-title" class="h-section">Experience</h2>
        <p class="font-mono text-xs text-text-muted">{{ $experiences->count() }} {{ Str::plural('role', $experiences->count()) }}</p>
    </div>

    <ol class="stagger">
        @foreach ($experiences as $experience)
            <li class="grid md:grid-cols-[var(--col-meta)_1fr] gap-x-12 gap-y-2 py-8 border-t border-border first:border-t-0 first:pt-0 last:pb-0">
                {{-- Date column: mono, muted, aligned to the role's cap height --}}
                <p class="self-start content-start font-mono text-xs text-text-muted md:pt-1 flex flex-wrap items-center gap-x-1.5 gap-y-1">
                    <time datetime="{{ $experience->start_date->format('Y-m') }}">{{ $experience->start_date->format('M Y') }}</time>
                    <span aria-hidden="true">–</span>
                    @if ($experience->end_date)
                        <time datetime="{{ $experience->end_date->format('Y-m') }}">{{ $experience->end_date->format('M Y') }}</time>
                    @else
                        <span class="badge badge--accent">now</span>
                    @endif
                </p>

                <div>
                    <h3 class="text-md font-medium text-text leading-snug tracking-[-0.01em]">{{ $experience->role }}</h3>
                    @if ($experience->company)
                        <p class="mt-0.5 text-sm text-text-secondary">
                            @if ($experience->company->website)
                                <a href="{{ $experience->company->website }}" target="_blank" rel="noopener" class="link link--quiet">{{ $experience->company->name }}<span class="sr-only"> (opens in a new tab)</span></a>
                            @else
                                {{ $experience->company->name }}
                            @endif
                        </p>
                    @endif

                    @if ($experience->description)
                        <p class="mt-stack-md text-base text-text-secondary max-w-measure">{{ $experience->description }}</p>
                    @endif

                    @if ($experience->technologies->count())
                        <ul class="mt-5 flex flex-wrap gap-2" aria-label="Technologies">
                            @foreach ($experience->technologies as $tech)
                                <li class="tag" style="{{ $tagStyle($tech) }}"><span class="tag__dot" aria-hidden="true"></span>{{ $tech->name }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</section>
@endif

{{-- ── PROJECTS ─────────────────────────────────────── --}}
@if ($projects->count())
<section id="projects" aria-labelledby="projects-title" class="section {{ $shell }} py-section border-t border-border">
    <div class="reveal section-head">
        <h2 id="projects-title" class="h-section">Projects</h2>
        <p class="font-mono text-xs text-text-muted">{{ $projects->count() }} {{ Str::plural('project', $projects->count()) }}</p>
    </div>

    <div class="stagger grid md:grid-cols-2 gap-6">
        @foreach ($projects as $project)
            @php $cover = $project->images->firstWhere('is_cover', true) ?? $project->images->first(); @endphp
            <article @class(['card', 'card--link' => $project->code]) aria-labelledby="project-{{ $project->id }}-title">
                <div class="card__media">
                    @if ($cover)
                        <img src="{{ Storage::url($cover->image) }}" alt="" loading="lazy" decoding="async" data-fit>
                    @else
                        <div class="w-full h-full flex items-center justify-center text-text-muted">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                            </svg>
                        </div>
                    @endif
                </div>

                <div class="flex flex-col flex-1 p-6">
                    <div class="flex items-start justify-between gap-4">
                        <h3 id="project-{{ $project->id }}-title" class="h-card">
                            @if ($project->code)
                                <a href="{{ route('projects.show', $project) }}" class="card__link">{{ $project->name }}</a>
                            @else
                                {{ $project->name }}
                            @endif
                        </h3>
                        @php
                            $statusClasses = match($project->status) {
                                'completed'   => 'badge--accent',
                                'in_progress' => 'badge--warn',
                                default       => '',
                            };
                        @endphp
                        <span class="badge {{ $statusClasses }} shrink-0 mt-1">
                            {{ match($project->status) {
                                'in_progress' => 'in progress',
                                'completed'   => 'completed',
                                'archived'    => 'archived',
                                default       => $project->status,
                            } }}
                        </span>
                    </div>

                    @if ($project->short_description)
                        <p class="mt-3 text-base text-text-secondary">{{ $project->short_description }}</p>
                    @endif

                    @if ($project->technologies->count())
                        <ul class="mt-5 flex flex-wrap gap-2" aria-label="Technologies">
                            @foreach ($project->technologies->take(4) as $tech)
                                <li class="tag" style="{{ $tagStyle($tech) }}"><span class="tag__dot" aria-hidden="true"></span>{{ $tech->name }}</li>
                            @endforeach
                            @if ($project->technologies->count() > 4)
                                <li class="tag text-text-muted" title="{{ $project->technologies->skip(4)->pluck('name')->join(', ') }}">+{{ $project->technologies->count() - 4 }}</li>
                            @endif
                        </ul>
                    @endif

                    @if ($project->repository_url || $project->demo_url)
                        {{-- mt-auto pins actions to the bottom so cards in a row align --}}
                        <div class="mt-auto pt-5">
                            <div class="flex items-center gap-6 border-t border-border pt-1">
                                @if ($project->demo_url)
                                    <a href="{{ $project->demo_url }}" target="_blank" rel="noopener noreferrer" class="action-link action-link--accent">
                                        Live<span class="sr-only"> site for {{ $project->name }} (opens in a new tab)</span>
                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7"/><path d="M8 7h9v9"/></svg>
                                    </a>
                                @endif
                                @if ($project->repository_url)
                                    <a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer" class="action-link">
                                        GitHub<span class="sr-only"> repository for {{ $project->name }} (opens in a new tab)</span>
                                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7"/><path d="M8 7h9v9"/></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif

{{-- ── CONTACT ──────────────────────────────────────── --}}
<section id="contact" aria-labelledby="contact-title" class="section {{ $shell }} py-section border-t border-border">
    <div class="reveal section-head mb-8">
        <h2 id="contact-title" class="h-section">Contact</h2>
    </div>

    @if ($profile)
        <div class="reveal" style="transition-delay: 100ms">
            {{-- The tagline now leads the hero, so the close uses your existing
                 fallback line. PLACEHOLDER COPY — edit freely. --}}
            <p class="font-serif text-2xl text-text tracking-[-0.025em] max-w-[20ch]">
                Always open to new opportunities and interesting projects. <em class="italic text-accent">Let's talk.</em>
            </p>

            <div class="mt-8 flex flex-wrap items-center gap-3">
                <button type="button" class="btn btn--primary" @click="$dispatch('open-contact')">
                    Contact me
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </button>
                @if ($profile->email_public)
                    <a href="mailto:{{ $profile->email_public }}" class="btn btn--ghost">or email directly</a>
                @endif
            </div>

            @php
                $socials = array_filter([
                    'GitHub'   => $profile->github_url,
                    'LinkedIn' => $profile->linkedin_url,
                    'Twitter'  => $profile->twitter_url,
                    'Website'  => $profile->website_url,
                ]);
            @endphp

            @if ($profile->email_public || count($socials))
                {{-- Same meta column as Experience dates: one left edge, one grid --}}
                <dl class="mt-block border-t border-border">
                    @if ($profile->email_public)
                        <div class="grid md:grid-cols-[var(--col-meta)_1fr] gap-x-12 gap-y-1 py-5 border-b border-border">
                            <dt class="eyebrow md:pt-1">Email</dt>
                            <dd><a href="mailto:{{ $profile->email_public }}" class="link text-base break-all">{{ $profile->email_public }}</a></dd>
                        </div>
                    @endif

                    @if (count($socials))
                        <div class="grid md:grid-cols-[var(--col-meta)_1fr] gap-x-12 gap-y-1 py-5 border-b border-border">
                            <dt class="eyebrow md:pt-1">Elsewhere</dt>
                            <dd class="flex flex-wrap gap-x-6 gap-y-1">
                                @foreach ($socials as $label => $url)
                                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="link text-base inline-flex items-center min-h-11 md:min-h-0">{{ $label }}<span class="sr-only"> (opens in a new tab)</span></a>
                                @endforeach
                            </dd>
                        </div>
                    @endif
{{--
                    @if ($profile->cv_path)
                        <div class="grid md:grid-cols-[var(--col-meta)_1fr] gap-x-12 gap-y-1 py-5 border-b border-border">
                            <dt class="eyebrow md:pt-1">Résumé</dt>
                            <dd><a href="{{ route('cv.download') }}" class="link text-base">Download CV ↓</a></dd>
                        </div>
                    @endif --}}
                </dl>
            @endif
        </div>
    @endif
</section>

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

{{-- ── CONTACT MODAL ────────────────────────────────── --}}
<div
    x-data="contactForm()"
    data-endpoint="{{ route('contact.send') }}"
    x-show="open"
    x-on:open-contact.window="open = true"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-200 bg-[var(--overlay)] backdrop-blur-xs flex items-end sm:items-center justify-center p-0 sm:p-4"
    @click.self="open = false"
    @keydown.escape.window="open = false"
    x-cloak
>
    <div
        x-ref="panel"
        role="dialog"
        aria-modal="true"
        aria-labelledby="contact-dialog-title"
        @keydown.tab="trapFocus($event)"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="bg-surface-2 border border-border-mid rounded-t-lg sm:rounded-lg w-full max-w-[30rem] max-h-[92svh] overflow-y-auto shadow-lg"
        @click.stop
    >
        <div class="flex items-center justify-between gap-4 pl-6 pr-4 py-4 border-b border-border">
            <h2 id="contact-dialog-title" class="h-card">Get in touch</h2>
            <button type="button" class="icon-btn" @click="open = false" aria-label="Close dialog">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="p-6">
            <template x-if="sent">
                <div class="text-center py-6" role="status">
                    <div class="size-12 rounded-full bg-[var(--accent-soft)] border border-[var(--accent-line)] text-accent flex items-center justify-center mx-auto mb-4" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </div>
                    <p class="h-card" tabindex="-1" data-autofocus>Message sent</p>
                    <p class="mt-1 text-sm text-text-secondary">Thanks for reaching out. I'll get back to you soon.</p>
                    <button type="button" class="btn btn--secondary mt-6" @click="open = false; sent = false">Close</button>
                </div>
            </template>

            <template x-if="!sent">
                <form @submit.prevent="submit" novalidate class="flex flex-col gap-5">
                    <div class="grid sm:grid-cols-2 gap-5 sm:gap-3">
                        <div class="flex flex-col gap-2">
                            <label class="field-label" for="contact-name">Name <span class="req" aria-hidden="true">*</span></label>
                            <input id="contact-name" type="text" class="field" x-model="form.name" placeholder="Your name" autocomplete="name" required
                                   :aria-invalid="!!errors.name" aria-describedby="contact-name-error">
                            <span id="contact-name-error" class="field-error" x-text="errors.name" x-show="errors.name"></span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="field-label" for="contact-email">Email <span class="req" aria-hidden="true">*</span></label>
                            <input id="contact-email" type="email" class="field" x-model="form.email" placeholder="you@example.com" autocomplete="email" required
                                   :aria-invalid="!!errors.email" aria-describedby="contact-email-error">
                            <span id="contact-email-error" class="field-error" x-text="errors.email" x-show="errors.email"></span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="field-label" for="contact-subject">Subject <span class="req">(optional)</span></label>
                        <input id="contact-subject" type="text" class="field" x-model="form.subject" placeholder="What's it about?">
                    </div>

                    <div class="flex flex-col gap-2">
                        <label class="field-label" for="contact-message">Message <span class="req" aria-hidden="true">*</span></label>
                        <textarea id="contact-message" class="field" x-model="form.message" placeholder="Tell me about your project or just say hello…" required
                                  :aria-invalid="!!errors.message" aria-describedby="contact-message-error"></textarea>
                        <span id="contact-message-error" class="field-error" x-text="errors.message" x-show="errors.message"></span>
                    </div>

                    <template x-if="serverError">
                        <p class="field-error" role="alert" x-text="serverError"></p>
                    </template>

                    <button type="submit" class="btn btn--primary btn--block" :disabled="sending" :aria-busy="sending.toString()">
                        <span x-show="!sending">Send message</span>
                        <span x-show="sending">Sending…</span>
                    </button>
                </form>
            </template>
        </div>
    </div>
</div>


</body>
</html>
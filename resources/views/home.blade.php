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

    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

@php
    $tagStyle = function (\App\Models\Technology $tech): string {
        $hex = $tech->technologyField?->color ?? '#6b7280';
        return "color:{$hex};border-color:{$hex}40;background:{$hex}0d;";
    };
    $firstName = Str::before($profile?->user?->nickname ?? config('app.name'), ' ');

    /* One shared gutter so nav, sections, and footer align to the
       same optical edge. px-6 on mobile, px-8 from md up. */
    $gutter = 'px-6 md:px-8';
    $shell  = "max-w-[var(--max-w)] mx-auto {$gutter}";
@endphp

{{-- ── NAV ─────────────────────────────────────────── --}}
<nav class="fixed top-0 left-0 right-0 z-100 h-[var(--nav-h)] bg-[rgba(250,250,248,0.72)] dark:bg-[rgba(16,15,13,0.72)] backdrop-blur-[20px] border-b-[0.5px] border-border">
    <div class="{{ $shell }} h-full flex items-center gap-8">
        <a href="#about" @click.prevent="scrollTo('about')" class="font-serif text-[1.15rem] text-text tracking-[-0.01em] shrink-0 hover:opacity-100">
            {{ $firstName }}<span class="text-accent">.</span>
        </a>

        <ul class="hidden md:flex items-center gap-9 list-none ml-auto">
            <li>
                <a href="#about" @click.prevent="scrollTo('about')" :class="activeSection === 'about' && 'active'" class="nav-link text-[0.8rem] font-normal text-text-mid tracking-[0.02em]">Home</a>
            </li>
            @if ($experiences->count())
                <li>
                    <a href="#experience" @click.prevent="scrollTo('experience')" :class="activeSection === 'experience' && 'active'" class="nav-link text-[0.8rem] font-normal text-text-mid tracking-[0.02em]">Experience</a>
                </li>
            @endif
            @if ($projects->count())
                <li>
                    <a href="#projects" @click.prevent="scrollTo('projects')" :class="activeSection === 'projects' && 'active'" class="nav-link text-[0.8rem] font-normal text-text-mid tracking-[0.02em]">Projects</a>
                </li>
            @endif
            <li>
                <a href="#contact" @click.prevent="scrollTo('contact')" :class="activeSection === 'contact' && 'active'" class="nav-link text-[0.8rem] font-normal text-text-mid tracking-[0.02em]">Contact</a>
            </li>
        </ul>

        <div class="flex items-center gap-3 shrink-0 md:ml-0 ml-auto">
            <button
                class="w-[32px] h-[32px] rounded-full border-[0.5px] border-border-mid bg-transparent cursor-pointer flex items-center justify-center text-text-mid transition-colors hover:border-border-strong hover:text-text hover:opacity-100"
                @click="dark = !dark"
                :aria-label="dark ? 'Switch to light mode' : 'Switch to dark mode'"
            >
                <svg x-show="!dark" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="5"/>
                    <line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                    <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                </svg>
                <svg x-show="dark" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                </svg>
            </button>

            <button class="md:hidden bg-none border-[0.5px] border-border-mid rounded-md px-[0.65rem] py-[0.4rem] cursor-pointer text-text-mid text-[0.85rem] leading-none hover:opacity-100 hover:text-text hover:border-border-strong transition-colors" @click="menuOpen = !menuOpen" :aria-label="menuOpen ? 'Close menu' : 'Open menu'">
                <span x-text="menuOpen ? '✕' : '☰'"></span>
            </button>
        </div>
    </div>

    <div class="{{ $gutter }} pt-4 pb-6 flex flex-col gap-4 border-t-[0.5px] border-border bg-[rgba(250,250,248,0.97)] dark:bg-[rgba(16,15,13,0.97)] backdrop-blur-[20px]" x-show="menuOpen" x-transition.opacity style="display: none;">
        <a href="#about" @click.prevent="scrollTo('about'); menuOpen = false" class="text-[0.9rem] font-normal text-text-mid hover:text-text hover:opacity-100">Home</a>
        @if ($experiences->count())
            <a href="#experience" @click.prevent="scrollTo('experience'); menuOpen = false" class="text-[0.9rem] font-normal text-text-mid hover:text-text hover:opacity-100">Experience</a>
        @endif
        @if ($projects->count())
            <a href="#projects" @click.prevent="scrollTo('projects'); menuOpen = false" class="text-[0.9rem] font-normal text-text-mid hover:text-text hover:opacity-100">Projects</a>
        @endif
        <a href="#contact" @click.prevent="scrollTo('contact'); menuOpen = false" class="text-[0.9rem] font-normal text-text-mid hover:text-text hover:opacity-100">Contact</a>
    </div>
</nav>

{{-- ── HERO ─────────────────────────────────────────── --}}
<section id="about" class="{{ $shell }} flex items-center min-h-[100svh] pt-[calc(var(--nav-h)+2rem)] pb-24">
    @if ($profile)
        <div class="w-full max-w-[640px] mx-auto flex flex-col items-center text-center">
            <p class="reveal rule-before rule-before--lg font-mono text-[0.68rem] font-normal tracking-[0.18em] uppercase text-text-dim mb-9 flex items-center gap-3">{{ $profile->job_title ?? 'Developer' }}</p>

            <h1 class="reveal font-serif text-[clamp(3.25rem,8vw,5.75rem)] max-md:text-[clamp(2.5rem,11vw,3.5rem)] font-normal leading-[1.02] tracking-[-0.03em] text-text mb-7" style="transition-delay: 0.08s">
                Hey, I'm<br><em class="italic text-accent">{{ str($profile->user?->name)->before(' ') ?: 'there' }}</em>
            </h1>

            @if ($profile->bio)
                <p class="reveal text-[1.0625rem] font-light leading-[1.8] text-text-soft mb-10 tracking-[0.005em] max-w-[520px]" style="transition-delay: 0.16s">{{ $profile->bio }}</p>
            @endif

            @if ($profile->cv_path)
                <div class="reveal flex items-center justify-center gap-3 flex-wrap" style="transition-delay: 0.24s">
                    <a href="{{ route('cv.download') }}" class="inline-flex items-center gap-[0.45rem] px-[1.5rem] py-[0.7rem] rounded-[2rem] text-[0.8rem] font-normal border-[0.5px] cursor-pointer transition-all tracking-[0.01em] bg-text text-bg border-text hover:opacity-85 hover:-translate-y-px">
                        Download resume
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="m19 12-7 7-7-7"/></svg>
                    </a>
                </div>
            @endif
        </div>
    @else
        <div class="w-full flex justify-center">
            <p class="rule-before rule-before--lg font-mono text-[0.68rem] font-normal tracking-[0.18em] uppercase text-text-dim flex items-center gap-3">Portfolio coming soon.</p>
        </div>
    @endif
</section>

{{-- ── EXPERIENCE ───────────────────────────────────── --}}
@if ($experiences->count())
<section id="experience" class="{{ $shell }} py-20 border-t-[0.5px] border-border">
    <div class="reveal rule-before rule-before--sm font-mono text-[0.66rem] font-medium tracking-[0.18em] uppercase text-text-dim mb-14 flex items-center gap-3">Experience</div>

    <div class="stagger flex flex-col">
        @foreach ($experiences as $experience)
            <div class="group grid grid-cols-[130px_1fr] max-md:grid-cols-1 gap-12 max-md:gap-3 py-9 max-md:py-7 border-b-[0.5px] border-border first:pt-0 last:border-b-0 last:pb-0">
                <div class="pt-[0.3rem] max-md:pt-0">
                    <div class="font-mono text-[0.68rem] text-text-dim leading-[1.6] tracking-[0.01em]">
                        {{ $experience->start_date->format('M Y') }}
                        @if ($experience->end_date)
                            <span class="text-text-dim/70">—</span> {{ $experience->end_date->format('M Y') }}
                        @else
                            <span class="text-text-dim/70">—</span> <span class="inline-flex items-center gap-[0.3rem] px-[0.5rem] py-[0.12rem] rounded-[2rem] font-mono text-[0.6rem] bg-[var(--accent-soft)] text-accent border-[0.5px] border-[var(--accent-line)] align-middle ml-[0.15rem]"><span class="w-[4px] h-[4px] rounded-full bg-accent inline-block"></span>now</span>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <div>
                        <span class="block text-[1rem] font-medium text-text tracking-[-0.015em] leading-[1.35]">{{ $experience->role }}</span>
                        @if ($experience->company)
                            <span class="block text-[0.82rem] font-normal text-text-mid mt-[0.15rem]">
                                @if ($experience->company->website)
                                    <a href="{{ $experience->company->website }}" target="_blank" rel="noopener" class="link-underline text-text-mid">{{ $experience->company->name }}</a>
                                @else
                                    {{ $experience->company->name }}
                                @endif
                            </span>
                        @endif
                    </div>

                    @if ($experience->description)
                        <p class="text-[0.9rem] font-light leading-[1.75] text-text-soft mt-[0.2rem] tracking-[0.005em]">{{ $experience->description }}</p>
                    @endif

                    @if ($experience->technologies->count())
                        <div class="flex flex-wrap gap-[0.35rem] mt-[0.6rem]">
                            @foreach ($experience->technologies as $tech)
                                <span class="px-[0.6rem] py-[0.2rem] rounded-[2rem] font-mono text-[0.65rem] font-normal bg-transparent border-[0.5px] border-border-mid text-text-mid whitespace-nowrap" style="{{ $tagStyle($tech) }}">{{ $tech->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

{{-- ── PROJECTS ─────────────────────────────────────── --}}
@if ($projects->count())
<section id="projects" class="{{ $shell }} py-20 border-t-[0.5px] border-border">
    <div class="reveal rule-before rule-before--sm font-mono text-[0.66rem] font-medium tracking-[0.18em] uppercase text-text-dim mb-14 flex items-center gap-3">Projects</div>

    <div class="stagger grid grid-cols-2 max-md:grid-cols-1 gap-5">
        @foreach ($projects as $project)
            @php $cover = $project->images->firstWhere('is_cover', true) ?? $project->images->first(); @endphp
            <div class="group bg-surface border-[0.5px] border-border rounded-2xl overflow-hidden flex flex-col transition-all duration-300 hover:border-border-mid hover:shadow-[0_10px_40px_-12px_rgba(0,0,0,0.12)] hover:-translate-y-1">
                @if ($cover)
                    <div class="overflow-hidden">
                        <img
                            src="{{ Storage::url($cover->image) }}"
                            alt="{{ $project->name }}"
                            class="w-full aspect-video object-cover block bg-bg-alt transition-transform duration-500 group-hover:scale-[1.03]"
                            loading="lazy"
                        >
                    </div>
                @else
                    <div class="w-full aspect-video bg-bg-alt flex items-center justify-center border-b-[0.5px] border-border">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-text-dim">
                            <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                        </svg>
                    </div>
                @endif

                <div class="px-6 pt-6 pb-6 flex flex-col gap-[0.7rem] flex-1">
                    <div class="flex justify-between items-start gap-3">
                        <span class="font-serif text-[1.2rem] font-normal text-text tracking-[-0.02em] leading-[1.25]">{{ $project->name }}</span>
                        @php
                            $statusClasses = match($project->status) {
                                'completed'   => 'bg-[var(--accent-soft)] text-accent border-[var(--accent-line)]',
                                'in_progress' => 'bg-[rgba(201,122,47,0.09)] text-[#c17d3a] dark:text-[#d9954f] border-[rgba(201,122,47,0.24)]',
                                'archived'    => 'bg-[rgba(0,0,0,0.04)] dark:bg-[rgba(255,255,255,0.04)] text-text-mid border-border-mid',
                                default       => 'bg-[rgba(0,0,0,0.04)] dark:bg-[rgba(255,255,255,0.04)] text-text-mid border-border-mid',
                            };
                        @endphp
                        <span class="font-mono text-[0.6rem] px-[0.55rem] py-[0.2rem] rounded-[2rem] uppercase tracking-[0.06em] shrink-0 mt-[0.25rem] font-normal border-[0.5px] {{ $statusClasses }}">
                            {{ match($project->status) {
                                'in_progress' => 'in progress',
                                'completed'   => 'completed',
                                'archived'    => 'archived',
                                default       => $project->status,
                            } }}
                        </span>
                    </div>

                    @if ($project->short_description)
                        <p class="text-[0.875rem] font-light leading-[1.75] text-text-soft flex-1 tracking-[0.005em]">{{ $project->short_description }}</p>
                    @endif

                    <div class="flex justify-between items-end gap-3 pt-4 border-t-[0.5px] border-border mt-auto">
                        @if ($project->technologies->count())
                            <div class="flex flex-wrap gap-[0.35rem]">
                                @foreach ($project->technologies->take(4) as $tech)
                                    <span class="px-[0.6rem] py-[0.2rem] rounded-[2rem] font-mono text-[0.65rem] font-normal bg-transparent border-[0.5px] border-border-mid text-text-mid whitespace-nowrap" style="{{ $tagStyle($tech) }}">{{ $tech->name }}</span>
                                @endforeach
                                @if ($project->technologies->count() > 4)
                                    <span class="px-[0.6rem] py-[0.2rem] rounded-[2rem] font-mono text-[0.65rem] font-normal bg-transparent border-[0.5px] border-border-mid text-text-dim whitespace-nowrap">+{{ $project->technologies->count() - 4 }}</span>
                                @endif
                            </div>
                        @else
                            <div></div>
                        @endif

                        @if ($project->repository_url || $project->demo_url)
                            <div class="flex gap-4 shrink-0">
                                @if ($project->repository_url)
                                    <a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer" class="font-mono text-[0.7rem] text-text-mid tracking-[0.02em] whitespace-nowrap transition-colors hover:text-text hover:opacity-100">GitHub →</a>
                                @endif
                                @if ($project->demo_url)
                                    <a href="{{ $project->demo_url }}" target="_blank" rel="noopener noreferrer" class="font-mono text-[0.7rem] text-accent tracking-[0.02em] whitespace-nowrap transition-colors hover:opacity-70 hover:opacity-100">live →</a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

{{-- ── CONTACT ──────────────────────────────────────── --}}
<section id="contact" class="{{ $shell }} py-20 border-t-[0.5px] border-border">
    <div class="reveal rule-before rule-before--sm font-mono text-[0.66rem] font-medium tracking-[0.18em] uppercase text-text-dim mb-14 flex items-center gap-3">Contact</div>

    @if ($profile)
        <div class="reveal" style="transition-delay: 0.1s">
            <div class="flex items-start justify-between gap-10 max-md:flex-col max-md:gap-6 mb-14">
                <p class="font-serif text-[clamp(1.5rem,3.5vw,1rem)] font-normal leading-[1.35] text-text max-w-[540px] mb-0 tracking-[-0.015em]">
                    {{ $profile->tagline ?? "Always open to new opportunities and interesting projects. Let's talk." }}
                </p>
                <button class="inline-flex items-center gap-[0.45rem] px-[1.5rem] py-[0.7rem] rounded-[2rem] text-[0.8rem] font-normal border-[0.5px] cursor-pointer transition-all tracking-[0.01em] bg-text text-bg border-text hover:opacity-85 hover:-translate-y-px shrink-0 mt-2 max-md:mt-0" @click="$dispatch('open-contact')">
                    Contact me
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </button>
            </div>

            <div class="grid grid-cols-[repeat(auto-fit,minmax(160px,1fr))] gap-8 pt-10 border-t-[0.5px] border-border">
                @if ($profile->email_public)
                    <div>
                        <div class="font-mono text-[0.62rem] uppercase tracking-[0.14em] text-text-dim mb-2.5">Email</div>
                        <div class="text-[0.875rem] font-light text-text leading-[1.5]">
                            <a href="mailto:{{ $profile->email_public }}" class="link-underline--strong text-text">{{ $profile->email_public }}</a>
                        </div>
                    </div>
                @endif

                @php
                    $socials = array_filter([
                        'GitHub'   => $profile->github_url,
                        'LinkedIn' => $profile->linkedin_url,
                        'Twitter'  => $profile->twitter_url,
                        'Website'  => $profile->website_url,
                    ]);
                @endphp

                @if (count($socials))
                    <div>
                        <div class="font-mono text-[0.62rem] uppercase tracking-[0.14em] text-text-dim mb-2.5 text-end">Connect</div>
                        <div class="text-[0.875rem] font-light text-text leading-[1.5] flex flex-wrap gap-[0.875rem] justify-end">
                            @foreach ($socials as $label => $url)
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="link-underline--strong text-text">{{ $label }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
{{-- 
                @if ($profile->cv_path)
                    <div>
                        <div class="font-mono text-[0.62rem] uppercase tracking-[0.14em] text-text-dim mb-2.5">Résumé</div>
                        <div class="text-[0.875rem] font-light text-text leading-[1.5]">
                            <a href="{{ route('cv.download') }}" class="link-underline--strong text-text">Download CV ↓</a>
                        </div>
                    </div>
                @endif --}}
            </div>
        </div>
    @endif
</section>

{{-- ── FOOTER ───────────────────────────────────────── --}}
<footer class="{{ $shell }} py-8 border-t-[0.5px] border-border flex justify-between items-center gap-4 max-md:flex-col max-md:items-start max-md:gap-4">
    <div class="flex items-center gap-[0.875rem]" x-data="clock()">
        <div class="font-mono text-[0.72rem] text-text-mid flex items-center gap-2">
            <span class="status-dot"></span>
            {{ 'Available' }}
        </div>
        <span class="text-border-strong font-sans">·</span>
        <div class="font-mono text-[0.72rem] text-text-mid flex items-center gap-2" x-text="currentTime"></div>
    </div>
    <p class="font-mono text-[0.68rem] text-text-dim tracking-[0.01em]">© {{ date('Y') }} {{ $profile?->user?->name ?? config('app.name') }}</p>
</footer>

{{-- ── CONTACT MODAL ────────────────────────────────── --}}
<div
    x-data="contactForm()"
    x-show="open"
    x-on:open-contact.window="open = true"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-200 bg-[rgba(0,0,0,0.4)] dark:bg-[rgba(0,0,0,0.6)] flex items-center justify-center p-4 backdrop-blur-[4px]"
    @click.self="open = false"
    @keydown.escape.window="open = false"
    style="display: none;"
>
    <div
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="bg-surface border-[0.5px] border-border-mid rounded-2xl w-full max-w-[480px] max-h-[90vh] overflow-y-auto shadow-[0_20px_60px_-15px_rgba(0,0,0,0.3)]"
        @click.stop
    >
        <div class="flex items-center justify-between px-6 py-5 border-b-[0.5px] border-border">
            <h3 class="font-serif text-[1.2rem] font-normal text-text tracking-[-0.01em]">Get in touch</h3>
            <button class="bg-none border-[0.5px] border-border-mid rounded-full w-7 h-7 flex items-center justify-center cursor-pointer text-text-mid transition-colors hover:border-border-strong hover:text-text hover:opacity-100" @click="open = false" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <div class="p-6">
            <template x-if="sent">
                <div class="text-center py-6">
                    <div class="w-11 h-11 rounded-full bg-[var(--accent-soft)] border-[0.5px] border-[var(--accent-line)] text-accent flex items-center justify-center mx-auto mb-4 text-[1.2rem]">✓</div>
                    <p class="font-serif text-[1.3rem] text-text mb-[0.4rem]">Message sent</p>
                    <p class="text-[0.85rem] font-light text-text-soft">Thanks for reaching out. I'll get back to you soon.</p>
                    <button class="inline-flex items-center gap-[0.4rem] px-[1.5rem] py-[0.65rem] rounded-[2rem] text-[0.8rem] font-normal border-[0.5px] cursor-pointer transition-all tracking-[0.01em] bg-transparent border-border-mid text-text-mid hover:border-border-strong hover:text-text hover:opacity-100 mt-6" @click="open = false; sent = false">Close</button>
                </div>
            </template>

            <template x-if="!sent">
                <form @submit.prevent="submit" novalidate>
                    <div class="grid grid-cols-2 max-md:grid-cols-1 gap-x-3 gap-y-4 mb-4">
                        <div class="flex flex-col gap-[0.4rem]">
                            <label class="text-[0.75rem] font-normal text-text-mid" for="contact-name">Name *</label>
                            <input id="contact-name" type="text" class="bg-bg border-[0.5px] border-border-mid rounded-lg px-3 py-[0.65rem] font-sans text-[0.875rem] font-light text-text transition-colors w-full outline-none placeholder:text-text-dim focus:border-accent" x-model="form.name" placeholder="Your name" autocomplete="name">
                            <span class="text-[0.72rem] text-[#c96060]" x-text="errors.name" x-show="errors.name"></span>
                        </div>
                        <div class="flex flex-col gap-[0.4rem]">
                            <label class="text-[0.75rem] font-normal text-text-mid" for="contact-email">Email *</label>
                            <input id="contact-email" type="email" class="bg-bg border-[0.5px] border-border-mid rounded-lg px-3 py-[0.65rem] font-sans text-[0.875rem] font-light text-text transition-colors w-full outline-none placeholder:text-text-dim focus:border-accent" x-model="form.email" placeholder="you@example.com" autocomplete="email">
                            <span class="text-[0.72rem] text-[#c96060]" x-text="errors.email" x-show="errors.email"></span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-[0.4rem] mb-4">
                        <label class="text-[0.75rem] font-normal text-text-mid" for="contact-subject">Subject</label>
                        <input id="contact-subject" type="text" class="bg-bg border-[0.5px] border-border-mid rounded-lg px-3 py-[0.65rem] font-sans text-[0.875rem] font-light text-text transition-colors w-full outline-none placeholder:text-text-dim focus:border-accent" x-model="form.subject" placeholder="What's it about?">
                    </div>

                    <div class="flex flex-col gap-[0.4rem]">
                        <label class="text-[0.75rem] font-normal text-text-mid" for="contact-message">Message *</label>
                        <textarea id="contact-message" class="bg-bg border-[0.5px] border-border-mid rounded-lg px-3 py-[0.65rem] font-sans text-[0.875rem] font-light text-text transition-colors w-full outline-none placeholder:text-text-dim focus:border-accent resize-y min-h-[110px]" x-model="form.message" placeholder="Tell me about your project or just say hello…"></textarea>
                        <span class="text-[0.72rem] text-[#c96060]" x-text="errors.message" x-show="errors.message"></span>
                    </div>

                    <template x-if="serverError">
                        <p class="text-[0.72rem] text-[#c96060] mb-3 mt-3" x-text="serverError"></p>
                    </template>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-[0.4rem] px-[1.5rem] py-[0.7rem] rounded-[2rem] text-[0.8rem] font-normal border-[0.5px] cursor-pointer transition-all tracking-[0.01em] bg-text text-bg border-text hover:opacity-85 w-full mt-5"
                        :disabled="sending"
                        :style="sending ? 'opacity: 0.7; cursor: not-allowed;' : ''"
                    >
                        <span x-show="!sending">Send message</span>
                        <span x-show="sending">Sending…</span>
                    </button>
                </form>
            </template>
        </div>
    </div>
</div>

<script>
function contactForm() {
    return {
        open: false,
        sending: false,
        sent: false,
        serverError: null,
        form: { name: '', email: '', subject: '', message: '' },
        errors: {},

        validate() {
            this.errors = {}
            if (!this.form.name.trim()) this.errors.name = 'Name is required.'
            if (!this.form.email.trim()) {
                this.errors.email = 'Email is required.'
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email)) {
                this.errors.email = 'Enter a valid email address.'
            }
            if (!this.form.message.trim()) this.errors.message = 'Message is required.'
            return Object.keys(this.errors).length === 0
        },

        async submit() {
            if (!this.validate()) return
            this.sending = true
            this.serverError = null

            try {
                const token = document.querySelector('meta[name="csrf-token"]')?.content ?? ''
                const response = await fetch('{{ route('contact.send') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                    },
                    body: JSON.stringify(this.form),
                })

                if (response.ok) {
                    this.sent = true
                    this.form = { name: '', email: '', subject: '', message: '' }
                } else {
                    const data = await response.json()
                    if (data.errors) {
                        this.errors = Object.fromEntries(
                            Object.entries(data.errors).map(([k, v]) => [k, Array.isArray(v) ? v[0] : v])
                        )
                    } else {
                        this.serverError = 'Something went wrong. Please try again.'
                    }
                }
            } catch {
                this.serverError = 'Network error. Please try again.'
            } finally {
                this.sending = false
            }
        },
    }
}
</script>

</body>
</html>
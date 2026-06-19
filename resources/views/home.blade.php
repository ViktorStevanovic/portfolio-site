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
        <title>{{ $profile->user?->name ?? config('app.name') }}</title>
        <meta name="description" content="{{ $profile->tagline ?? Str::limit($profile->bio, 160) }}">
    @else
        <title>{{ config('app.name') }}</title>
    @endif

    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

@php
    $tagColor = fn(string $name): string => ['tag-0','tag-1','tag-2','tag-3','tag-4','tag-5'][abs(crc32($name)) % 6];
    $firstName = Str::before($profile?->user?->name ?? config('app.name'), ' ');
@endphp

{{-- ── NAV ─────────────────────────────────────────── --}}
<nav class="nav">
    <div class="nav__inner">
        <a href="#about" @click.prevent="scrollTo('about')" class="nav__logo">
            {{ $firstName }}<span class="nav__logo-dot">.</span>
        </a>

        <ul class="nav__links">
            <li>
                <a href="#about" @click.prevent="scrollTo('about')" :class="activeSection === 'about' && 'active'">Home</a>
            </li>
            @if ($experiences->count())
                <li>
                    <a href="#experience" @click.prevent="scrollTo('experience')" :class="activeSection === 'experience' && 'active'">Experience</a>
                </li>
            @endif
            @if ($projects->count())
                <li>
                    <a href="#projects" @click.prevent="scrollTo('projects')" :class="activeSection === 'projects' && 'active'">Projects</a>
                </li>
            @endif
            <li>
                <a href="#contact" @click.prevent="scrollTo('contact')" :class="activeSection === 'contact' && 'active'">Contact</a>
            </li>
        </ul>

        <div class="nav__right">
            <button class="nav__cta" @click="$dispatch('open-contact')">get in touch</button>

            <button
                class="theme-toggle"
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

            <button class="nav__hamburger" @click="menuOpen = !menuOpen" :aria-label="menuOpen ? 'Close menu' : 'Open menu'">
                <span x-text="menuOpen ? '✕' : '☰'"></span>
            </button>
        </div>
    </div>

    <div class="nav__mobile" x-show="menuOpen" x-transition.opacity style="display: none;">
        <a href="#about" @click.prevent="scrollTo('about'); menuOpen = false">Home</a>
        @if ($experiences->count())
            <a href="#experience" @click.prevent="scrollTo('experience'); menuOpen = false">Experience</a>
        @endif
        @if ($projects->count())
            <a href="#projects" @click.prevent="scrollTo('projects'); menuOpen = false">Projects</a>
        @endif
        <a href="#contact" @click.prevent="scrollTo('contact'); menuOpen = false">Contact</a>
    </div>
</nav>

{{-- ── HERO ─────────────────────────────────────────── --}}
<section id="about" class="section section--hero">
    @if ($profile)
        <div style="width: 100%">
            <p class="hero__eyebrow reveal">{{ $profile->job_title ?? 'Developer' }}</p>

            <h1 class="hero__name reveal" style="transition-delay: 0.1s">
                Hey!<em>I'm {{ $profile->user?->name ?? 'there' }}</em>
            </h1>

            @if ($profile->bio)
                <p class="hero__bio reveal" style="transition-delay: 0.2s">{{ $profile->bio }}</p>
            @endif

            <div class="hero__actions reveal" style="transition-delay: 0.3s">
                @if ($profile->cv_path)
                    <a href="{{ route('cv.download') }}" class="btn btn--primary">↓ Resume</a>
                @endif
                @if ($profile->github_url)
                    <a href="{{ $profile->github_url }}" target="_blank" rel="noopener noreferrer" class="btn btn--ghost">GitHub</a>
                @endif
                @if ($profile->linkedin_url)
                    <a href="{{ $profile->linkedin_url }}" target="_blank" rel="noopener noreferrer" class="btn btn--ghost">LinkedIn</a>
                @endif
                @if ($profile->twitter_url)
                    <a href="{{ $profile->twitter_url }}" target="_blank" rel="noopener noreferrer" class="btn btn--ghost">Twitter</a>
                @endif
                @if ($profile->email_public)
                    <a href="mailto:{{ $profile->email_public }}" class="btn btn--ghost">Email</a>
                @endif
            </div>

            <div class="hero__meta reveal" x-data="clock()" style="transition-delay: 0.4s">
                <div class="hero__meta-item">
                    <span class="status-dot"></span>
                    {{ $profile->job_title ?? 'Available' }}
                </div>
                <span class="hero__meta-sep">·</span>
                <div class="hero__meta-item" x-text="currentTime"></div>
            </div>
        </div>
    @else
        <p class="hero__eyebrow" style="opacity: 1">Portfolio coming soon.</p>
    @endif
</section>

{{-- ── EXPERIENCE ───────────────────────────────────── --}}
@if ($experiences->count())
<section id="experience" class="section">
    <div class="section__label reveal">Experience</div>

    <div class="timeline stagger">
        @foreach ($experiences as $experience)
            <div class="timeline__item">
                <div class="timeline__aside">
                    <div class="timeline__dates">
                        {{ $experience->start_date->format('M Y') }}
                        @if ($experience->end_date)
                            — {{ $experience->end_date->format('M Y') }}
                        @else
                            — <span class="badge--now">now</span>
                        @endif
                    </div>
                </div>

                <div class="timeline__body">
                    <div>
                        <span class="timeline__role">{{ $experience->role }}</span>
                        @if ($experience->company)
                            <span class="timeline__company">
                                @if ($experience->company->website)
                                    <a href="{{ $experience->company->website }}" target="_blank" rel="noopener">{{ $experience->company->name }}</a>
                                @else
                                    {{ $experience->company->name }}
                                @endif
                            </span>
                        @endif
                    </div>

                    @if ($experience->type)
                        <span class="timeline__type">{{ str_replace('_', ' ', $experience->type) }}</span>
                    @endif

                    @if ($experience->description)
                        <p class="timeline__desc">{{ $experience->description }}</p>
                    @endif

                    @if ($experience->technologies->count())
                        <div class="tags">
                            @foreach ($experience->technologies as $tech)
                                <span class="tag {{ $tagColor($tech->name) }}">{{ $tech->name }}</span>
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
<section id="projects" class="section">
    <div class="section__label reveal">Projects</div>

    <div class="projects-grid stagger">
        @foreach ($projects as $project)
            @php $cover = $project->images->firstWhere('is_cover', true) ?? $project->images->first(); @endphp
            <div class="project-card">
                @if ($cover)
                    <img
                        src="{{ Storage::url($cover->image) }}"
                        alt="{{ $project->name }}"
                        class="project-cover"
                        loading="lazy"
                    >
                @else
                    <div class="project-cover-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-dim)">
                            <rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                        </svg>
                    </div>
                @endif

                <div class="project-card__body">
                    <div class="project-card__top">
                        <span class="project-card__name">{{ $project->name }}</span>
                        <span class="project-card__status project-card__status--{{ $project->status }}">
                            {{ match($project->status) {
                                'in_progress' => 'in progress',
                                'completed'   => 'completed',
                                'archived'    => 'archived',
                                default       => $project->status,
                            } }}
                        </span>
                    </div>

                    @if ($project->short_description)
                        <p class="project-card__desc">{{ $project->short_description }}</p>
                    @endif

                    <div class="project-card__footer">
                        @if ($project->technologies->count())
                            <div class="tags">
                                @foreach ($project->technologies->take(4) as $tech)
                                    <span class="tag {{ $tagColor($tech->name) }}">{{ $tech->name }}</span>
                                @endforeach
                                @if ($project->technologies->count() > 4)
                                    <span class="tag">+{{ $project->technologies->count() - 4 }}</span>
                                @endif
                            </div>
                        @else
                            <div></div>
                        @endif

                        @if ($project->repository_url || $project->demo_url)
                            <div class="project-card__links">
                                @if ($project->repository_url)
                                    <a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer" class="project-card__link">GitHub →</a>
                                @endif
                                @if ($project->demo_url)
                                    <a href="{{ $project->demo_url }}" target="_blank" rel="noopener noreferrer" class="project-card__link">live →</a>
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
<section id="contact" class="section">
    <div class="section__label reveal">Contact</div>

    @if ($profile)
        <div class="reveal" style="transition-delay: 0.1s">
            <p class="contact__text">
                {{ $profile->tagline ?? "I'm always open to new opportunities and interesting projects. Feel free to reach out." }}
            </p>

            <div class="contact__details">
                @if ($profile->email_public)
                    <div>
                        <div class="contact__detail-label">Email</div>
                        <div class="contact__detail-value">
                            <a href="mailto:{{ $profile->email_public }}">{{ $profile->email_public }}</a>
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
                        <div class="contact__detail-label">Connect</div>
                        <div class="contact__detail-value contact__detail-value--links">
                            @foreach ($socials as $label => $url)
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer">{{ $label }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($profile->cv_path)
                    <div>
                        <div class="contact__detail-label">Resume</div>
                        <div class="contact__detail-value">
                            <a href="{{ route('cv.download') }}">Download CV ↓</a>
                        </div>
                    </div>
                @endif
            </div>

            <button class="btn btn--ghost" @click="$dispatch('open-contact')">
                get in touch →
            </button>
        </div>
    @endif
</section>

{{-- ── FOOTER ───────────────────────────────────────── --}}
<footer class="footer">
    <div class="footer__links">
        @if ($profile?->github_url)
            <a href="{{ $profile->github_url }}" target="_blank" rel="noopener noreferrer">GitHub</a>
        @endif
        @if ($profile?->linkedin_url)
            <a href="{{ $profile->linkedin_url }}" target="_blank" rel="noopener noreferrer">LinkedIn</a>
        @endif
        @if ($profile?->twitter_url)
            <a href="{{ $profile->twitter_url }}" target="_blank" rel="noopener noreferrer">Twitter</a>
        @endif
    </div>
    <p class="footer__copy">built with Laravel · {{ $profile?->user?->name ?? config('app.name') }}</p>
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
    class="modal-backdrop"
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
        class="modal"
        @click.stop
    >
        <div class="modal__header">
            <h3 class="modal__title">Get in touch</h3>
            <button class="modal__close" @click="open = false" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <div class="modal__body">
            <template x-if="sent">
                <div class="form-success">
                    <div class="form-success__icon">✓</div>
                    <p class="form-success__title">Message sent!</p>
                    <p class="form-success__sub">Thanks for reaching out. I'll get back to you soon.</p>
                    <button class="btn btn--ghost" style="margin-top: 1.25rem;" @click="open = false; sent = false">Close</button>
                </div>
            </template>

            <template x-if="!sent">
                <form @submit.prevent="submit" novalidate>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="contact-name">Name *</label>
                            <input id="contact-name" type="text" class="form-input" x-model="form.name" placeholder="Your name" autocomplete="name">
                            <span class="form-error" x-text="errors.name" x-show="errors.name"></span>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="contact-email">Email *</label>
                            <input id="contact-email" type="email" class="form-input" x-model="form.email" placeholder="you@example.com" autocomplete="email">
                            <span class="form-error" x-text="errors.email" x-show="errors.email"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="contact-subject">Subject</label>
                        <input id="contact-subject" type="text" class="form-input" x-model="form.subject" placeholder="What's it about?">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="contact-message">Message *</label>
                        <textarea id="contact-message" class="form-textarea" x-model="form.message" placeholder="Tell me about your project or just say hello…"></textarea>
                        <span class="form-error" x-text="errors.message" x-show="errors.message"></span>
                    </div>

                    <template x-if="serverError">
                        <p class="form-error" style="margin-bottom: 0.75rem" x-text="serverError"></p>
                    </template>

                    <button
                        type="submit"
                        class="btn btn--primary"
                        style="width: 100%; justify-content: center;"
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

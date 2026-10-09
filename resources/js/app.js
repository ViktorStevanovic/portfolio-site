import Alpine from 'alpinejs'

window.Alpine = Alpine

Alpine.data('siteData', () => ({
    dark: localStorage.getItem('dark') === 'true',
    activeSection: 'about',
    menuOpen: false,

    init() {
        this.$watch('dark', (val) => localStorage.setItem('dark', String(val)))

        const sections = document.querySelectorAll('.section[id]')
        const sectionObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting && entry.target.id) {
                        this.activeSection = entry.target.id
                    }
                })
            },
            { threshold: 0.3 }
        )
        sections.forEach((s) => sectionObserver.observe(s))

        const revealObserver = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible')
                        revealObserver.unobserve(entry.target)
                    }
                })
            },
            { threshold: 0.1 }
        )
        document.querySelectorAll('.reveal, .stagger').forEach((el) => revealObserver.observe(el))
    },

    scrollTo(id) {
        document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' })
    },
}))

Alpine.data('clock', () => ({
    currentTime: '',
    interval: null,

    init() {
        this.tick()
        this.interval = setInterval(() => this.tick(), 1000)
    },

    tick() {
        this.currentTime = new Date().toLocaleTimeString('en-GB', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
        })
    },

    destroy() {
        clearInterval(this.interval)
    },
}))

/* Contact modal. The endpoint comes from data-endpoint on the modal
   root, because Blade's route() can't run inside a Vite bundle. */
Alpine.data('contactForm', () => ({
    open: false,
    sending: false,
    sent: false,
    serverError: null,
    form: { name: '', email: '', subject: '', message: '' },
    errors: {},
    returnFocusTo: null,

    init() {
        this.$watch('open', (isOpen) => {
            document.documentElement.classList.toggle('modal-open', isOpen)
            if (isOpen) {
                this.returnFocusTo = document.activeElement
                this.$nextTick(() => requestAnimationFrame(() => {
                    (this.$refs.panel.querySelector('input') ?? this.focusables()[0])?.focus()
                }))
            } else {
                this.returnFocusTo?.focus?.()
            }
        })
        this.$watch('sent', (isSent) => {
            if (isSent) this.$nextTick(() => this.$refs.panel.querySelector('[data-autofocus]')?.focus())
        })
    },

    focusables() {
        return [...this.$refs.panel.querySelectorAll(
            'a[href], button:not([disabled]), input:not([disabled]), textarea:not([disabled]), select, [tabindex]:not([tabindex="-1"])'
        )].filter((el) => el.offsetParent !== null)
    },

    trapFocus(e) {
        const els = this.focusables()
        if (!els.length) return
        const first = els[0], last = els[els.length - 1]
        if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus() }
        else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus() }
    },

    validate() {
        this.errors = {}
        if (!this.form.name.trim()) this.errors.name = 'Name is required.'
        if (!this.form.email.trim()) {
            this.errors.email = 'Email is required.'
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email)) {
            this.errors.email = 'Enter a valid email address.'
        }
        if (!this.form.message.trim()) this.errors.message = 'Message is required.'
        const valid = Object.keys(this.errors).length === 0
        if (!valid) this.$nextTick(() => this.$refs.panel.querySelector('[aria-invalid="true"]')?.focus())
        return valid
    },

    async submit() {
        if (!this.validate()) return
        this.sending = true
        this.serverError = null

        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.content ?? ''
            const response = await fetch(this.$root.dataset.endpoint, {
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
                const data = await response.json().catch(() => ({}))
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
}))

/* Project gallery + lightbox. `items` is [{ src, alt, caption }],
   passed from Blade with @js(). Keyboard: ←/→ to move, Esc to close;
   swipe on touch screens. Focus is trapped while open and restored after. */
Alpine.data('gallery', (items = []) => ({
    items,
    index: 0,
    open: false,
    returnFocusTo: null,
    touchX: null,

    init() {
        this.$watch('open', (isOpen) => {
            document.documentElement.classList.toggle('modal-open', isOpen)
            if (isOpen) {
                this.returnFocusTo = document.activeElement
                this.$nextTick(() => requestAnimationFrame(() => this.$refs.lightboxClose?.focus()))
            } else {
                this.returnFocusTo?.focus?.()
            }
        })
    },

    get current() {
        return this.items[this.index] ?? {}
    },

    get counter() {
        return `${this.index + 1} / ${this.items.length}`
    },

    show(i) {
        this.index = i
        this.open = true
    },

    next() {
        if (this.items.length > 1) this.index = (this.index + 1) % this.items.length
    },

    prev() {
        if (this.items.length > 1) this.index = (this.index - 1 + this.items.length) % this.items.length
    },

    onKey(e) {
        if (!this.open) return
        if (e.key === 'ArrowRight') { e.preventDefault(); this.next() }
        else if (e.key === 'ArrowLeft') { e.preventDefault(); this.prev() }
        else if (e.key === 'Escape') { this.open = false }
    },

    trapFocus(e) {
        const els = [...this.$refs.lightbox.querySelectorAll('button:not([disabled])')]
            .filter((el) => el.offsetParent !== null)
        if (!els.length) return
        const first = els[0], last = els[els.length - 1]
        if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus() }
        else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus() }
    },

    touchStart(e) {
        this.touchX = e.changedTouches[0].clientX
    },

    touchEnd(e) {
        if (this.touchX === null) return
        const dx = e.changedTouches[0].clientX - this.touchX
        if (Math.abs(dx) > 50) dx < 0 ? this.next() : this.prev()
        this.touchX = null
    },
}))

/* Images marked data-fit fill their frame by default (screenshots).
   If an image would need upscaling at all in either
   direction to fill it (an icon or logo), it gets .is-small and is
   shown at natural size instead. */
function fitImage(img) {
    const frame = img.parentElement
    if (!img.naturalWidth || !frame) return
    const small = img.naturalWidth < frame.clientWidth
               || img.naturalHeight < frame.clientHeight
    img.classList.toggle('is-small', small)
}
document.addEventListener('load', (e) => {
    if (e.target instanceof HTMLImageElement && e.target.hasAttribute('data-fit')) fitImage(e.target)
}, true)
document.querySelectorAll('img[data-fit]').forEach((img) => img.complete && fitImage(img))

Alpine.start()
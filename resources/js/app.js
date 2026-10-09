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

Alpine.start()
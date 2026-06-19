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

Alpine.start()

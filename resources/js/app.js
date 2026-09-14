import Alpine from 'alpinejs';
import AOS from 'aos';
import gsap from 'gsap';
// PRD Section 8.4/10.2 — aos/dist/aos.css is NOT imported here: it is ~26KB
// and ~97% unused (this project only ever uses data-aos="fade-up"), which
// Lighthouse flagged under unused-css-rules. An equivalent minimal
// [data-aos] ruleset lives in resources/css/app.css instead.

window.Alpine = Alpine;

Alpine.start();

// PRD Section 8.4 — scroll reveal via AOS with custom (non-default) config.
AOS.init({
    duration: 700,
    easing: 'ease-out-cubic',
    once: true,
    offset: 80,
});

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/**
 * PRD Section 8.2/8.4 — GSAP count-up for statistic numbers, triggered once
 * an element enters the viewport. Falls back to the final value instantly
 * for prefers-reduced-motion.
 */
function initCountUpStats() {
    document.querySelectorAll('[data-count-up]').forEach((el) => {
        const target = parseFloat(el.dataset.countUp);
        const decimals = el.dataset.countUp.includes('.') ? 1 : 0;

        if (Number.isNaN(target)) {
            return;
        }

        if (prefersReducedMotion) {
            el.textContent = target.toFixed(decimals);
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                observer.unobserve(el);

                const proxy = { value: 0 };
                gsap.to(proxy, {
                    value: target,
                    duration: 1.8,
                    ease: 'power2.out',
                    onUpdate: () => {
                        el.textContent = proxy.value.toFixed(decimals);
                    },
                });
            });
        }, { threshold: 0.4 });

        observer.observe(el);
    });
}

/**
 * PRD Section 8.4 — GSAP-eased scroll progress indicator in the navbar.
 */
function initNavbarProgress() {
    const bar = document.querySelector('[data-scroll-progress]');
    if (!bar) {
        return;
    }

    const state = { progress: 0 };

    const update = () => {
        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const target = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;

        if (prefersReducedMotion) {
            bar.style.width = `${target}%`;
            return;
        }

        gsap.to(state, {
            progress: target,
            duration: 0.3,
            ease: 'power1.out',
            overwrite: true,
            onUpdate: () => {
                bar.style.width = `${state.progress}%`;
            },
        });
    };

    window.addEventListener('scroll', update, { passive: true });
    update();
}

document.addEventListener('DOMContentLoaded', () => {
    initCountUpStats();
    initNavbarProgress();
});

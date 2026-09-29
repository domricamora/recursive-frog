import Alpine from 'alpinejs';

/* ---------------------------------------------------------------------
 | Recursive Frog front-end
 | Deliberately small: Alpine for interactivity, plus scroll reveals and
 | the GA4 event bridge described in plan.md #37.
 --------------------------------------------------------------------- */

window.Alpine = Alpine;
window.rfTrack = rfTrack;

/**
 * Push a GA4 event through the dataLayer. Safe to call when GTM/GA4 are
 * not configured — the event simply sits in the queue or is discarded.
 */
export function rfTrack(name, params = {}) {
    if (typeof window === 'undefined') return;

    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
        event: name,
        ...params,
        page_path: window.location.pathname,
    });
}

/* Track every primary CTA click once, wherever it lives. */
document.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-track-cta]');
    if (!trigger) return;

    rfTrack('cta_click', {
        cta_location: trigger.dataset.trackCta || 'unknown',
        cta_label: trigger.textContent.trim().slice(0, 80),
    });
});

/* Accordion / FAQ open tracking. */
document.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-track-faq]');
    if (!trigger) return;

    rfTrack('faq_open', { faq_question: trigger.dataset.trackFaq || '' });
});

/* Form-start signal on the first meaningful interaction. */
document.addEventListener('focusin', (event) => {
    const form = event.target.closest('form[data-track-form]');
    if (!form || form.dataset.formStarted === '1') return;

    form.dataset.formStarted = '1';
    rfTrack('contact_form_start', { form_id: form.dataset.trackForm });
});

/* Tier selection is a real intent signal, so send it immediately. */
document.addEventListener('change', (event) => {
    const field = event.target.closest('[data-track-tier]');
    if (!field) return;

    rfTrack('tier_select', { tier_name: field.value });
});

/* Fill UTM + referrer hidden inputs so attribution survives the submit. */
function seedAttribution() {
    const params = new URLSearchParams(window.location.search);
    const targets = {
        utm_source: params.get('utm_source'),
        utm_medium: params.get('utm_medium'),
        utm_campaign: params.get('utm_campaign'),
        utm_term: params.get('utm_term'),
        utm_content: params.get('utm_content'),
        source_url: params.get('source_url') || document.referrer || window.location.href,
    };

    for (const [key, value] of Object.entries(targets)) {
        if (!value) continue;

        document.querySelectorAll(`[name="${key}"]`).forEach((input) => {
            if (!input.value) input.value = value;
        });
    }
}

/* Reveal-on-scroll, with a no-JS fallback that simply shows everything. */
function initReveals() {
    const items = document.querySelectorAll('.reveal');

    if (!('IntersectionObserver' in window)) {
        items.forEach((item) => item.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        },
        { rootMargin: '0px 0px -12% 0px', threshold: 0.08 },
    );

    items.forEach((item) => observer.observe(item));
}

/**
 * Background film.
 *
 * Every <video data-film> shows its poster until real frames arrive, is paused
 * while off screen so a long page never decodes six clips at once, and stays
 * still entirely when the visitor asks for reduced motion or the browser
 * reports a metered / slow connection.
 */
function initFilms() {
    const videos = document.querySelectorAll('video[data-film]');
    if (!videos.length) return;

    const still = window.matchMedia('(prefers-reduced-motion: reduce)');

    const connection = navigator.connection;
    const frugal = connection && (connection.saveData || /^(slow-)?2g$/.test(connection.effectiveType || ''));

    const play = (video) => {
        if (still.matches || frugal) return;
        const attempt = video.play();
        // Autoplay can still be refused (low power mode, strict policies).
        if (attempt && typeof attempt.catch === 'function') attempt.catch(() => {});
    };

    if (!('IntersectionObserver' in window)) {
        videos.forEach(play);
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) play(entry.target);
                else entry.target.pause();
            });
        },
        // Start slightly before the panel is on screen, stop well after.
        { rootMargin: '120px 0px', threshold: 0.01 },
    );

    videos.forEach((video) => {
        video.muted = true;
        video.setAttribute('playsinline', '');
        video.setAttribute('aria-hidden', 'true');

        // The poster is already in the markup as the fallback frame; only fade
        // the video in once the browser reports it can actually paint frames.
        const reveal = () => video.setAttribute('data-state', 'playing');
        if (video.readyState >= 2) reveal();

        video.addEventListener('playing', reveal, { once: true });
        video.addEventListener('loadeddata', () => {
            if (video.readyState >= 2) reveal();
        }, { once: true });

        // If frames never arrive, the poster simply stays put.
        video.addEventListener('error', () => video.removeAttribute('data-state'), { once: true });

        observer.observe(video);
    });
}

/**
 * Carousel rails. The markup ships plain overflow-x scrollers, so the buttons
 * simply nudge scrollLeft — no carousel library, and the rail still works with
 * a trackpad, touch or keyboard if JavaScript never runs.
 */
function initRails() {
    document.querySelectorAll('[data-rail]').forEach((wrap) => {
        const rail = wrap.querySelector('.rail');
        if (!rail) return;

        const step = () => {
            const card = rail.querySelector(':scope > *');
            return card ? card.getBoundingClientRect().width + 16 : rail.clientWidth * 0.8;
        };

        const update = () => {
            const max = rail.scrollWidth - rail.clientWidth - 2;
            wrap.querySelectorAll('[data-rail-prev]').forEach((b) => { b.disabled = rail.scrollLeft <= 2; });
            wrap.querySelectorAll('[data-rail-next]').forEach((b) => { b.disabled = rail.scrollLeft >= max; });
        };

        wrap.querySelectorAll('[data-rail-prev]').forEach((button) => {
            button.addEventListener('click', () => rail.scrollBy({ left: -step(), behavior: 'smooth' }));
        });

        wrap.querySelectorAll('[data-rail-next]').forEach((button) => {
            button.addEventListener('click', () => rail.scrollBy({ left: step(), behavior: 'smooth' }));
        });

        rail.addEventListener('scroll', update, { passive: true });
        window.addEventListener('resize', update);
        update();
    });
}

/** Live Manila clock for the top-left pill. */
function initClock() {
    const nodes = document.querySelectorAll('[data-clock]');
    if (!nodes.length) return;

    const paint = () => {
        const parts = new Intl.DateTimeFormat('en-GB', {
            timeZone: 'Asia/Manila',
            hour: '2-digit',
            minute: '2-digit',
            hour12: false,
        }).format(new Date());

        const [hours, minutes] = parts.split(':');

        nodes.forEach((node) => {
            node.querySelector('[data-clock-time]').textContent = `${hours}:${minutes}`;
            node.setAttribute('title', `Cebu City — ${parts} (GMT+8)`);
        });
    };

    paint();
    // Align to the next minute boundary, then tick on the minute.
    setTimeout(() => {
        paint();
        setInterval(paint, 60_000);
    }, (60 - new Date().getSeconds()) * 1000);
}

/** "You've scrolled 2m" readout in the footer, plus the top chrome state. */
function initScrollReadout() {
    const readouts = document.querySelectorAll('[data-scroll-depth]');
    const chrome = document.querySelector('[data-chrome]');
    if (!readouts.length && !chrome) return;

    let queued = false;

    const update = () => {
        queued = false;
        const y = window.scrollY;

        if (chrome) chrome.setAttribute('data-stuck', y > 24 ? 'true' : 'false');
        if (!readouts.length) return;

        // Approximate: one metre of reading per 1000px of scroll.
        const metres = Math.max(0, Math.round(y / 1000));

        readouts.forEach((node) => {
            const target = node.querySelector('[data-scroll-depth-value]') || node;
            const label = `${metres}m`;
            if (target.textContent !== label) target.textContent = label;
        });
    };

    window.addEventListener('scroll', () => {
        if (queued) return;
        queued = true;
        requestAnimationFrame(update);
    }, { passive: true });

    update();
}

function boot() {
    seedAttribution();
    initReveals();
    initFilms();
    initRails();
    initClock();
    initScrollReadout();

    // Staggered hero entrance: each element opts in with data-reveal-order.
    requestAnimationFrame(() => {
        document.querySelectorAll('[data-reveal-order]').forEach((el) => {
            el.style.transitionDelay = `${Number(el.dataset.revealOrder) * 90}ms`;
            el.classList.add('is-visible');
        });
    });
}

Alpine.start();

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot);
} else {
    boot();
}

/* ---------- Sticky header ---------- */
const header = document.getElementById('header');
const onScroll = () => {
  if (window.scrollY > 30) header.classList.add('scrolled');
  else header.classList.remove('scrolled');
};
window.addEventListener('scroll', onScroll, { passive: true });
onScroll();

/* ---------- Mobile menu ---------- */
const burger = document.getElementById('burger');
const navLinks = document.getElementById('navLinks');

const closeMenu = () => {
  navLinks.classList.remove('open');
  burger.classList.remove('open');
  header.classList.remove('menu-open');
  burger.setAttribute('aria-expanded', 'false');
  document.body.style.overflow = '';
};

burger.addEventListener('click', () => {
  const open = navLinks.classList.toggle('open');
  burger.classList.toggle('open', open);
  header.classList.toggle('menu-open', open);
  burger.setAttribute('aria-expanded', open);
  document.body.style.overflow = open ? 'hidden' : '';
});

navLinks.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMenu));

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    if (navLinks.classList.contains('open')) closeMenu();
    if (videoModal && videoModal.classList.contains('open')) closeVideoModal();
  }
});

window.addEventListener('resize', () => {
  if (window.innerWidth > 900 && navLinks.classList.contains('open')) closeMenu();
});

/* ---------- Scroll reveal ---------- */
const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('in');
      revealObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });

document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

/* ---------- Animated counters ---------- */
const counters = document.querySelectorAll('[data-count]');
const counterObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (!entry.isIntersecting) return;
    const el = entry.target;
    const target = parseFloat(el.dataset.count);
    const suffix = el.dataset.suffix || '';
    const duration = 1600;
    const start = performance.now();

    const tick = (now) => {
      const p = Math.min((now - start) / duration, 1);
      const eased = 1 - Math.pow(1 - p, 3);
      el.textContent = Math.round(target * eased) + suffix;
      if (p < 1) requestAnimationFrame(tick);
      else el.textContent = target + suffix;
    };
    requestAnimationFrame(tick);
    counterObserver.unobserve(el);
  });
}, { threshold: 0.5 });

counters.forEach(c => counterObserver.observe(c));

/* ---------- Active nav link on scroll ---------- */
const sections = document.querySelectorAll('section[id]');
const navAnchors = document.querySelectorAll('.nav-links a');
const sectionObserver = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const id = entry.target.getAttribute('id');
      navAnchors.forEach(a => {
        a.classList.toggle('active', a.getAttribute('href') === '#' + id);
      });
    }
  });
}, { threshold: 0.35, rootMargin: '-20% 0px -55% 0px' });

sections.forEach(s => sectionObserver.observe(s));

/* ---------- Video Modal ---------- */
const videoModal = document.getElementById('videoModal');
const videoFrame = document.getElementById('videoFrame');
const videoTitle = document.getElementById('videoTitle');
const videoDesc  = document.getElementById('videoDesc');

const DIRECT_VIDEO_RE = /\.(mp4|webm|ogv|ogg|mov|m4v|avi|mkv)(\?.*)?$/i;

function resolveVideoSource(url){
  if (!url) return { type: 'none', src: '' };

  const yt =
    url.match(/(?:youtube\.com\/watch\?(?:.*&)?v=)([\w-]{11})/) ||
    url.match(/(?:youtu\.be\/)([\w-]{11})/) ||
    url.match(/(?:youtube\.com\/shorts\/)([\w-]{11})/) ||
    url.match(/(?:youtube\.com\/live\/)([\w-]{11})/) ||
    url.match(/(?:youtube\.com\/embed\/)([\w-]{11})/);

  if (yt && yt[1]) {
    return {
      type: 'iframe',
      src: 'https://www.youtube-nocookie.com/embed/' + yt[1] +
           '?autoplay=1&rel=0&modestbranding=1&playsinline=1' +
           '&origin=' + encodeURIComponent(window.location.origin)
    };
  }

  const vm = url.match(/vimeo\.com\/(?:video\/)?(\d+)/);
  if (vm && vm[1]) {
    return {
      type: 'iframe',
      src: 'https://player.vimeo.com/video/' + vm[1] + '?autoplay=1'
    };
  }

  if (DIRECT_VIDEO_RE.test(url) || /^(https?:|\/|\.\/|\.\.\/)/i.test(url)) {
    return { type: 'video', src: url };
  }

  return { type: 'iframe', src: url };
}

function escapeAttr(str){
  return String(str).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function openVideoModal(card){
  const title = card.getAttribute('data-title') || 'Video Tour';
  const desc  = card.getAttribute('data-desc')  || '';
  const raw   = (card.getAttribute('data-video') || '').trim();
  const { type, src } = resolveVideoSource(raw);

  videoTitle.textContent = title;
  videoDesc.textContent  = desc;

  if (type === 'video') {
    videoFrame.innerHTML =
      '<video src="' + escapeAttr(src) + '" ' +
      'controls autoplay playsinline preload="metadata" ' +
      'controlsList="nodownload">' +
      'Your browser does not support the video tag.' +
      '</video>';
  } else if (type === 'iframe') {
    videoFrame.innerHTML =
      '<iframe src="' + escapeAttr(src) + '" ' +
      'title="' + escapeAttr(title) + '" ' +
      'referrerpolicy="strict-origin-when-cross-origin" ' +
      'allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" ' +
      'allowfullscreen loading="lazy"></iframe>';
  } else {
    videoFrame.innerHTML =
      '<div class="video-placeholder">' +
        '<div class="vp-inner">' +
          '<div class="vp-icon"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg></div>' +
          '<h3>' + escapeAttr(title) + '</h3>' +
          '<p>' + escapeAttr(desc) + '</p>' +
        '</div>' +
      '</div>';
  }

  videoModal.classList.add('open');
  videoModal.setAttribute('aria-hidden', 'false');
  document.body.style.overflow = 'hidden';
}

function closeVideoModal(){
  videoModal.classList.remove('open');
  videoModal.setAttribute('aria-hidden', 'true');
  videoFrame.innerHTML = '';
  if (!navLinks.classList.contains('open')) document.body.style.overflow = '';
}

document.querySelectorAll('.v-card').forEach(card => {
  card.addEventListener('click', () => openVideoModal(card));
});

videoModal.querySelectorAll('[data-close]').forEach(el => {
  el.addEventListener('click', closeVideoModal);
});

/* ---------- Form handling ---------- */
const form = document.getElementById('quoteForm');

if (form) {
  form.addEventListener('submit', (e) => {
    const name = document.getElementById('name');
    const email = document.getElementById('email');
    const country = document.getElementById('country');
    let valid = true;

    [name, email, country].forEach(field => {
      field.style.borderColor = '';
      if (!field.value.trim() || (field.type === 'email' && !/^\S+@\S+\.\S+$/.test(field.value))) {
        field.style.borderColor = '#d9534f';
        valid = false;
      }
    });

    // Only stop submission if validation fails
    if (!valid) {
      e.preventDefault();
      return;
    }

    // Valid — show button state and let the form submit to Laravel normally.
    // Do NOT disable the button: disabled buttons are excluded from submission.
    const btn = form.querySelector('button[type="submit"]');
    if (btn) btn.innerHTML = 'Sending...';
  });
}

/* ---------- Footer year ---------- */
const yearEl = document.getElementById('year');
if (yearEl) yearEl.textContent = new Date().getFullYear();
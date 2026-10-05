/* ============================================================
   CONTACT MODAL
   ============================================================ */
(function () {
  const modal = document.getElementById('contactModal');
  if (! modal) return;

  function openModal(e) {
    if (e) e.preventDefault();
    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('contact-modal-open');

    // Autofocus the first field after the animation starts
    setTimeout(() => {
      const first = modal.querySelector('input, textarea, select');
      if (first) first.focus();
    }, 120);
  }

  function closeModal() {
    modal.classList.remove('open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('contact-modal-open');
  }

  /* Openers — anything with [data-open-contact] */
  document.querySelectorAll('[data-open-contact]').forEach(btn => {
    btn.addEventListener('click', openModal);
  });

  /* Closers */
  modal.querySelectorAll('[data-close-contact]').forEach(el => {
    el.addEventListener('click', closeModal);
  });

  /* Escape key */
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('open')) {
      closeModal();
    }
  });

  /* Auto-open if the URL hash is #contact when the page loads */
  if (window.location.hash === '#contact') {
    // Small delay so the page has settled
    setTimeout(openModal, 200);
  }

  /* Close on success message inside the modal */
  const successFlash = modal.querySelector('.form-success');
  if (successFlash && successFlash.classList.contains('show')) {
    setTimeout(closeModal, 3000);
  }
})();

/* If the page loaded with a contact flash or errors, auto-open the modal */
const modal = document.getElementById('contactModal');
const hasContactFlash = document.querySelector('.contact-modal__flash');

if (modal && (hasContactFlash || window.location.hash === '#contact')) {
  setTimeout(() => {
    modal.classList.add('open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('contact-modal-open');
  }, 200);
}
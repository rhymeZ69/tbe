/* ---------- User dropdown ---------- */
const userWrap = document.getElementById('userMenuWrap');
const userBtn  = document.getElementById('userMenuBtn');

if (userBtn) {
  userBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    const open = userWrap.classList.toggle('open');
    userBtn.setAttribute('aria-expanded', open);
  });

  document.addEventListener('click', (e) => {
    if (! userWrap.contains(e.target)) {
      userWrap.classList.remove('open');
      userBtn.setAttribute('aria-expanded', 'false');
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      userWrap.classList.remove('open');
      userBtn.setAttribute('aria-expanded', 'false');
    }
  });
}
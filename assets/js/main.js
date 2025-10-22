<script>
  const hamburger = document.querySelector('.hamburger');
  const mobileNav = document.getElementById('mobileNav');
  const mobileClose = mobileNav?.querySelector('.close');
  const openSearch = document.getElementById('openSearch');
  const searchOverlay = document.getElementById('searchOverlay');
  const searchClose = searchOverlay?.querySelector('.close');

  hamburger?.addEventListener('click', () => {
    const expanded = hamburger.getAttribute('aria-expanded') === 'true';
    hamburger.setAttribute('aria-expanded', String(!expanded));
    mobileNav.hidden = expanded;
    if (!expanded) mobileNav.querySelector('a,button,summary')?.focus();
  });
  mobileClose?.addEventListener('click', () => {
    hamburger.setAttribute('aria-expanded', 'false');
    mobileNav.hidden = true;
    hamburger.focus();
  });

  openSearch?.addEventListener('click', () => { searchOverlay.hidden = false; searchOverlay.querySelector('input')?.focus(); });
  searchClose?.addEventListener('click', () => { searchOverlay.hidden = true; openSearch.focus(); });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      if (!searchOverlay?.hidden) { searchOverlay.hidden = true; openSearch?.focus(); }
      if (!mobileNav?.hidden) { mobileNav.hidden = true; hamburger?.setAttribute('aria-expanded','false'); hamburger?.focus(); }
    }
  });

  // Open mega menu with keyboard
  document.querySelectorAll('.nav-dropdown').forEach(dd => {
    const btn = dd.querySelector('.nav-link');
    btn.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowDown') dd.querySelector('.mega a')?.focus();
    });
  });
</script>

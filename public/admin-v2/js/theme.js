/**
 * Dark mode controller for the v2 design system.
 * Framework-free (no jQuery dependency), safe to load alongside the
 * existing jQuery-based admin panel.
 *
 * Usage:
 *   <html data-theme="light">          (set eagerly, see inline snippet below)
 *   <button data-theme-toggle>Toggle theme</button>
 *
 * To avoid a flash of the wrong theme, inline this small snippet directly
 * in <head>, before any stylesheet, on real pages later:
 *
 *   <script>
 *     (function () {
 *       var stored = localStorage.getItem('bestow-theme');
 *       var theme = stored || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
 *       document.documentElement.setAttribute('data-theme', theme);
 *     })();
 *   </script>
 *
 * This file (theme.js) then only needs to wire up the toggle button(s)
 * and keep localStorage in sync — it can load normally at the end of body.
 */
(function () {
  var STORAGE_KEY = 'bestow-theme';

  function getStoredTheme() {
    return localStorage.getItem(STORAGE_KEY);
  }

  function systemPrefersDark() {
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
  }

  function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
      btn.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
    });
  }

  function setTheme(theme) {
    localStorage.setItem(STORAGE_KEY, theme);
    applyTheme(theme);
  }

  function currentTheme() {
    return document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
  }

  // Ensure a theme is set even if the no-flash inline snippet wasn't added
  // to this particular page yet.
  if (!document.documentElement.getAttribute('data-theme')) {
    applyTheme(getStoredTheme() || (systemPrefersDark() ? 'dark' : 'light'));
  }

  document.addEventListener('click', function (e) {
    var toggle = e.target.closest('[data-theme-toggle]');
    if (!toggle) return;
    setTheme(currentTheme() === 'dark' ? 'light' : 'dark');
  });

  // Keep in sync if the user changes OS theme and has never explicitly chosen
  if (window.matchMedia) {
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
      if (getStoredTheme()) return; // explicit choice always wins
      applyTheme(e.matches ? 'dark' : 'light');
    });
  }
})();

/**
 * Layout controller for the v2 admin shell (sidebar, mobile off-canvas,
 * group collapse/expand, search focus shortcut). Framework-free, no jQuery
 * dependency — safe to load alongside the existing jQuery-based pages.
 */
(function () {
  var SIDEBAR_COLLAPSED_KEY = 'bestow-sidebar-collapsed';

  var sidebar = document.getElementById('sidebarV2');
  var backdrop = document.getElementById('sidebarBackdrop');
  var collapseBtn = document.getElementById('sidebarCollapseBtn');
  var mobileToggle = document.getElementById('sidebarMobileToggle');

  // ---- Desktop collapse (icon-only) ---------------------------------
  if (sidebar && localStorage.getItem(SIDEBAR_COLLAPSED_KEY) === '1') {
    sidebar.classList.add('collapsed');
  }
  if (collapseBtn) {
    collapseBtn.addEventListener('click', function () {
      var collapsed = sidebar.classList.toggle('collapsed');
      localStorage.setItem(SIDEBAR_COLLAPSED_KEY, collapsed ? '1' : '0');
    });
  }

  // ---- Mobile off-canvas ---------------------------------------------
  function openMobileSidebar() {
    sidebar.classList.add('mobile-open');
    backdrop.classList.add('show');
  }
  function closeMobileSidebar() {
    sidebar.classList.remove('mobile-open');
    backdrop.classList.remove('show');
  }
  if (mobileToggle) {
    mobileToggle.addEventListener('click', function () {
      sidebar.classList.contains('mobile-open') ? closeMobileSidebar() : openMobileSidebar();
    });
  }
  if (backdrop) {
    backdrop.addEventListener('click', closeMobileSidebar);
  }

  // ---- Group expand/collapse ------------------------------------------
  document.addEventListener('click', function (e) {
    var toggle = e.target.closest('.sidebar-group-toggle');
    if (!toggle) return;
    var group = toggle.closest('.sidebar-group');
    var submenu = group.querySelector('.sidebar-submenu');
    var isOpen = group.classList.contains('open');

    if (isOpen) {
      group.classList.remove('open');
      submenu.style.display = 'none';
    } else {
      group.classList.add('open');
      submenu.style.display = 'flex';
    }
  });

  // ---- Search focus shortcut ("/" key) --------------------------------
  document.addEventListener('keydown', function (e) {
    if (e.key !== '/' || e.metaKey || e.ctrlKey || e.altKey) return;
    var active = document.activeElement;
    var isTyping = active && (active.tagName === 'INPUT' || active.tagName === 'TEXTAREA' || active.isContentEditable);
    if (isTyping) return;

    var search = document.querySelector('[data-shortcut-focus]');
    if (search) {
      e.preventDefault();
      search.focus();
    }
  });
})();

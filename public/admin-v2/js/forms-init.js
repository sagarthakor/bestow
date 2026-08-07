/**
 * Auto-initializes Select2 and Flatpickr on any matching field, so pages
 * just add the data attribute / class and get the behavior for free.
 *
 * Select2:   <select data-v2-select2>...</select>
 * Flatpickr: <input data-v2-datepicker>                 (single date)
 *            <input data-v2-datepicker="range">          (range picker)
 *            <input data-v2-datepicker-format="Y-m-d">    (custom format, default d-m-Y)
 */
(function ($) {
  function initSelect2() {
    if (typeof $ === 'undefined' || !$.fn.select2) return;
    $('[data-v2-select2]').each(function () {
      var $el = $(this);
      if ($el.data('select2')) return;
      $el.select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: $el.data('placeholder') || $el.attr('data-placeholder') || 'Select...',
        allowClear: !$el.prop('required'),
      });
    });
  }

  function initFlatpickr() {
    if (typeof flatpickr === 'undefined') return;
    document.querySelectorAll('[data-v2-datepicker]').forEach(function (el) {
      if (el._flatpickr) return;
      var mode = el.getAttribute('data-v2-datepicker');
      flatpickr(el, {
        dateFormat: el.getAttribute('data-v2-datepicker-format') || 'd-m-Y',
        mode: mode === 'range' ? 'range' : 'single',
        allowInput: true,
      });
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    initSelect2();
    initFlatpickr();
  });

  // Expose for pages that inject fields dynamically (e.g. add-row in a
  // line-item table) to re-run after inserting new markup.
  window.BestowFormsInit = { initSelect2: initSelect2, initFlatpickr: initFlatpickr };
})(window.jQuery);

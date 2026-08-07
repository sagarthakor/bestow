/**
 * Auto-initializes any <table data-v2-datatable> on the page with:
 * sticky header (FixedHeader), built-in search, export buttons
 * (copy/csv/excel/pdf/print), and responsive column collapsing.
 *
 * Usage:
 *   <table data-v2-datatable data-page-length="25">...</table>
 *
 * Per-column filter dropdowns (status, category, etc.) are plain HTML
 * <select>/<input> elements you build yourself in a `.table-toolbar` above
 * the table (see components/v2/table.blade.php) — wire them up with
 * `.search()` on the DataTable instance, e.g.:
 *
 *   var table = $('#myTable').DataTable();
 *   $('#statusFilter').on('change', function () {
 *     table.column(4).search(this.value).draw();
 *   });
 */
(function ($) {
  if (typeof $ === 'undefined' || !$.fn.DataTable) return;

  $(function () {
    $('table[data-v2-datatable]').each(function () {
      var $table = $(this);
      if ($.fn.DataTable.isDataTable(this)) return;

      // If the page already paginates server-side (Laravel's ->paginate()),
      // set data-paging="false" to let DataTables only handle sticky
      // header / in-page search / export, without a second pager UI.
      var serverPaged = $table.data('paging') === false || $table.attr('data-paging') === 'false';

      $table.DataTable({
        paging: !serverPaged,
        info: !serverPaged,
        pageLength: parseInt($table.data('page-length'), 10) || 10,
        order: [],
        dom: "<'table-toolbar-inline d-flex justify-content-between align-items-center flex-wrap gap-2'<'dt-buttons-slot'B><'dt-search-slot'f>>" +
             "rt" +
             (serverPaged ? '' : "<'d-flex justify-content-between align-items-center flex-wrap gap-2 p-3'<'dataTables_info_slot'i><'dataTables_paginate_slot'p>>"),
        fixedHeader: {
          header: true,
          headerOffset: document.querySelector('.topbar-v2') ? document.querySelector('.topbar-v2').offsetHeight : 0,
        },
        responsive: true,
        buttons: [
          { extend: 'copyHtml5', className: 'btn btn-secondary btn-sm' },
          { extend: 'csvHtml5', className: 'btn btn-secondary btn-sm' },
          { extend: 'excelHtml5', className: 'btn btn-secondary btn-sm' },
          { extend: 'pdfHtml5', className: 'btn btn-secondary btn-sm' },
          { extend: 'print', className: 'btn btn-secondary btn-sm' },
        ],
        language: {
          search: '',
          searchPlaceholder: 'Search...',
          paginate: { previous: '‹', next: '›' },
        },
      });
    });
  });
})(window.jQuery);

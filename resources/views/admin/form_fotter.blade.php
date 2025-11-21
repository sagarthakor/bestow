<script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="/adminpanel/default/assets/js/jquery.min.js"></script>
        <script src="/adminpanel/default/assets/js/bootstrap.min.js"></script>
        <script src="/adminpanel/default/assets/js/detect.js"></script>
        <script src="/adminpanel/default/assets/js/fastclick.js"></script>
        <script src="/adminpanel/default/assets/js/jquery.blockUI.js"></script>
        <script src="/adminpanel/default/assets/js/waves.js"></script>
        <script src="/adminpanel/default/assets/js/jquery.slimscroll.js"></script>
        <script src="/adminpanel/default/assets/js/jquery.scrollTo.min.js"></script>
        <script src="/adminpanel/plugins/switchery/switchery.min.js"></script>

        <script src="/adminpanel/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js"></script>
        <script type="text/javascript" src="/adminpanel/plugins/multiselect/js/jquery.multi-select.js"></script>
        <script type="text/javascript" src="/adminpanel/plugins/jquery-quicksearch/jquery.quicksearch.js"></script>
        <script src="/adminpanel/plugins/select2/js/select2.min.js" type="text/javascript"></script>
        <script src="/adminpanel/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
        <script src="/adminpanel/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js" type="text/javascript"></script>
        <script src="/adminpanel/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js" type="text/javascript"></script>
        <script src="/adminpanel/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js" type="text/javascript"></script>


        <script type="text/javascript" src="/adminpanel/plugins/autocomplete/countries.js"></script>
        <script type="text/javascript" src="/adminpanel/default/assets/pages/jquery.autocomplete.init.js"></script>

        <script type="text/javascript" src="/adminpanel/default/assets/pages/jquery.form-advanced.init.js"></script>


        <!-- App js -->
        <script src="/adminpanel/default/assets/js/jquery.core.js"></script>
        <script src="/adminpanel/default/assets/js/jquery.app.js"></script>
        <script src="/adminpanel/default/assets/js/jquery-1.12.4.js"></script>
  <script src="/adminpanel/default/assets/js/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#start_date" ).datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd-mm-yy'
    });
  } );
  </script>
  <script>
  $( function() {
    $( "#end_date" ).datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd-mm-yy'
    });
  } );

   $( function() {
    $( "#support_expiry_date" ).datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd-mm-yy'
    });
  } );

   $( function() {
    $( "#support_start_date" ).datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd-mm-yy'
    });
  } );

   $( function() {
    $( "#support_start_date" ).datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: 'dd-mm-yy'
    });
  } );

  $(document).on('focus', '.select2-selection.select2-selection--single', function (e) {
      $(this).closest(".select2-container").siblings('select:enabled').select2('open');
  });

  // steal focus during close - only capture once and stop propogation
  $('select.select2').on('select2:closing', function (e) {
      $(e.target).data("select2").$selection.one('focus focusin', function (e) {
          e.stopPropagation();
      });
  });
  </script>

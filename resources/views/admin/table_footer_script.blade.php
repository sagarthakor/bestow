 <script>
            var resizefunc = [];
        </script>

        <!-- jQuery  -->
        <script src="{{asset('public/adminpanel/default/assets/js/jquery.min.js')}}"></script>
        <script src="{{asset('public/adminpanel/default/assets/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('public/adminpanel/default/assets/js/detect.js')}}"></script>
        <script src="{{asset('public/adminpanel/default/assets/js/fastclick.js')}}"></script>
        <script src="{{asset('public/adminpanel/default/assets/js/jquery.blockUI.js')}}"></script>
        <script src="{{asset('public/adminpanel/default/assets/js/waves.js')}}"></script>
        <script src="{{asset('public/adminpanel/default/assets/js/jquery.slimscroll.js')}}"></script>
        <script src="{{asset('public/adminpanel/default/assets/js/jquery.scrollTo.min.js')}}"></script>
        <script src="{{asset('public/adminpanel/plugins/switchery/switchery.min.js')}}"></script>

        <script src="{{asset('public/adminpanel/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('public/adminpanel/plugins/datatables/dataTables.bootstrap.js')}}"></script>

        <script src="{{asset('public/adminpanel/plugins/datatables/dataTables.buttons.min.js')}}"></script>
        <script src="{{asset('public/adminpanel/plugins/datatables/buttons.bootstrap.min.js')}}"></script>
        <script src="{{asset('public/adminpanel/plugins/datatables/jszip.min.js')}}"></script>
        <script src="{{asset('public/adminpanel/plugins/datatables/pdfmake.min.js')}}"></script>
        <script src="{{asset('public/adminpanel/plugins/datatables/vfs_fonts.js')}}"></script>
        <script src="{{asset('public/adminpanel/plugins/datatables/buttons.html5.min.js')}}"></script>
        <script src="{{asset('public/adminpanel/plugins/datatables/buttons.print.min.js')}}"></script>
        <script src="{{asset('public/adminpanel/plugins/datatables/dataTables.fixedHeader.min.js')}}"></script>
        <script src="{{asset('public/adminpanel/plugins/datatables/dataTables.keyTable.min.js')}}"></script>
        <script src="{{asset('public/adminpanel/plugins/datatables/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('public/adminpanel/plugins/datatables/responsive.bootstrap.min.js')}}"></script>
        <script src="{{asset('public/adminpanel/plugins/datatables/dataTables.scroller.min.js')}}"></script>
        <script src="{{asset('public/adminpanel/plugins/datatables/dataTables.colVis.js')}}"></script>
        <script src="{{asset('public/adminpanel/plugins/datatables/dataTables.fixedColumns.min.js')}}"></script>

        <!-- init -->
        <script src="{{asset('public/adminpanel/default/assets/pages/jquery.datatables.init.js')}}"></script>

        <!-- App js -->
        <script src="{{asset('public/adminpanel/default/assets/js/jquery.core.js')}}"></script>
        <script src="{{asset('public/adminpanel/default/assets/js/jquery.app.js')}}"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                $('#datatable').dataTable({
    "bInfo" : true,
    "paging": false,
    "ordering": false,
    "searching": false,
     dom: 'Bfrtip',
        buttons: [
            {
                extend:    'copyHtml5',
                text:      'Copy',
                titleAttr: 'Copy'
            },
            {
                extend:    'excelHtml5',
                text:      'Excel',
                titleAttr: 'Excel'
            },
            {
                extend:    'csvHtml5',
                text:      'CSV',
                titleAttr: 'CSV'
            },
            {
                extend:    'pdfHtml5',
                text:      'PDF',
                titleAttr: 'PDF',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            }
        ]
});
                $('#datatable-keytable').DataTable({keys: true});
                $('#datatable-responsive').DataTable();
                $('#datatable-colvid').DataTable({
                    "dom": 'C<"clear">lfrtip',
                    "colVis": {
                        "buttonText": "Change columns"
                    }
                });
                $('#datatable-scroller').DataTable({
                    ajax: "{{asset('public/adminpanel/plugins/datatables/json/scroller-demo.json')}}",
                    deferRender: true,
                    scrollY: 380,
                    scrollCollapse: true,
                    scroller: true
                });
                var table = $('#datatable-fixed-header').DataTable({fixedHeader: true});
                var table = $('#datatable-fixed-col').DataTable({
                    scrollY: "300px",
                    scrollX: true,
                    scrollCollapse: true,
                    paging: false,
                    fixedColumns: {
                        leftColumns: 1,
                        rightColumns: 1
                    }
                });
            });
            TableManageButtons.init();

        </script>

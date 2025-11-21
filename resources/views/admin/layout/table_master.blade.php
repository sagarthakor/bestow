<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="{{ env('APP_NAME') }} - @yield('title')">
        <meta name="author" content="Coderthemes">

        <!-- App favicon -->
        <link rel="shortcut icon" href="/assets/images/favicon.ico">
        <!-- App title -->
        <title>@yield('title') - {{ env('APP_NAME') }}</title>

        <link href="/admin/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link href="/admin/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/admin/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/admin/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/admin/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/admin/plugins/datatables/dataTables.colVis.css" rel="stylesheet" type="text/css"/>
        <link href="/admin/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/admin/plugins/datatables/fixedColumns.dataTables.min.css" rel="stylesheet" type="text/css"/>


        <!-- App css -->
        <link href="/admin/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/admin/assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="/admin/assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="/admin/assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="/admin/assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="/admin/assets/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="/admin/assets/css/responsive.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="/admin/plugins/switchery/switchery.min.css">


        <style>
            table th{
                text-align:center !important;
            }
            .listSearchContributor {
                min-height: 28px;
                width: 100%;
                min-width: 100px;
            }
            .inputElement {
                height: 30px;
                width: 100%;
                border-radius: 1px;
                box-shadow: none;
                border: 1px solid #cccccc;
            }
            input[type="text"].inputElement, input[type="password"].inputElement {
                padding: 3px 8px;
            }




        </style>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <body class="fixed-left">
            @include("admin.layout.menu")
            @yield('content')
            <footer class="footer text-right">
                {{now()->format('Y')}} {{env('APP_NAME')}}
            </footer>

         </div>


         <!-- ============================================================== -->
         <!-- End Right content here -->
         <!-- ============================================================== -->


         <!-- Right Sidebar -->
         <div class="side-bar right-bar">
             <a href="javascript:void(0);" class="right-bar-toggle">
                 <i class="mdi mdi-close-circle-outline"></i>
             </a>
             <h4 class="">Settings</h4>
             <div class="setting-list nicescroll">
                 <div class="row m-t-20">
                     <div class="col-xs-8">
                         <h5 class="m-0">Notifications</h5>
                         <p class="text-muted m-b-0"><small>Do you need them?</small></p>
                     </div>
                     <div class="col-xs-4 text-right">
                         <input type="checkbox" checked data-plugin="switchery" data-color="#7fc1fc" data-size="small"/>
                     </div>
                 </div>

                 <div class="row m-t-20">
                     <div class="col-xs-8">
                         <h5 class="m-0">API Access</h5>
                         <p class="m-b-0 text-muted"><small>Enable/Disable access</small></p>
                     </div>
                     <div class="col-xs-4 text-right">
                         <input type="checkbox" checked data-plugin="switchery" data-color="#7fc1fc" data-size="small"/>
                     </div>
                 </div>

                 <div class="row m-t-20">
                     <div class="col-xs-8">
                         <h5 class="m-0">Auto Updates</h5>
                         <p class="m-b-0 text-muted"><small>Keep up to date</small></p>
                     </div>
                     <div class="col-xs-4 text-right">
                         <input type="checkbox" checked data-plugin="switchery" data-color="#7fc1fc" data-size="small"/>
                     </div>
                 </div>

                 <div class="row m-t-20">
                     <div class="col-xs-8">
                         <h5 class="m-0">Online Status</h5>
                         <p class="m-b-0 text-muted"><small>Show your status to all</small></p>
                     </div>
                     <div class="col-xs-4 text-right">
                         <input type="checkbox" checked data-plugin="switchery" data-color="#7fc1fc" data-size="small"/>
                     </div>
                 </div>
             </div>
         </div>
         <!-- /Right-bar -->

     </div>
     <!-- END wrapper -->


     <script>
        var resizefunc = [];
    </script>

    <!-- jQuery  -->
    <script src="/admin/assets/js/jquery.min.js"></script>
    <script src="/admin/assets/js/bootstrap.min.js"></script>
    <script src="/admin/assets/js/detect.js"></script>
    <script src="/admin/assets/js/fastclick.js"></script>
    <script src="/admin/assets/js/jquery.blockUI.js"></script>
    <script src="/admin/assets/js/waves.js"></script>
    <script src="/admin/assets/js/jquery.slimscroll.js"></script>
    <script src="/admin/assets/js/jquery.scrollTo.min.js"></script>
    <script src="/admin/plugins/switchery/switchery.min.js"></script>

    <script src="/admin/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/admin/plugins/datatables/dataTables.bootstrap.js"></script>

    <script src="/admin/plugins/datatables/dataTables.buttons.min.js"></script>
    <script src="/admin/plugins/datatables/buttons.bootstrap.min.js"></script>
    <script src="/admin/plugins/datatables/jszip.min.js"></script>
    <script src="/admin/plugins/datatables/pdfmake.min.js"></script>
    <script src="/admin/plugins/datatables/vfs_fonts.js"></script>
    <script src="/admin/plugins/datatables/buttons.html5.min.js"></script>
    <script src="/admin/plugins/datatables/buttons.print.min.js"></script>
    <script src="/admin/plugins/datatables/dataTables.fixedHeader.min.js"></script>
    <script src="/admin/plugins/datatables/dataTables.keyTable.min.js"></script>
    <script src="/admin/plugins/datatables/dataTables.responsive.min.js"></script>
    <script src="/admin/plugins/datatables/responsive.bootstrap.min.js"></script>
    <script src="/admin/plugins/datatables/dataTables.scroller.min.js"></script>
    <script src="/admin/plugins/datatables/dataTables.colVis.js"></script>
    <script src="/admin/plugins/datatables/dataTables.fixedColumns.min.js"></script>

    <!-- init -->
    <script src="/admin/assets/pages/jquery.datatables.init.js"></script>

    <!-- App js -->
    <script src="/admin/assets/js/jquery.core.js"></script>
    <script src="/admin/assets/js/jquery.app.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#datatable').dataTable();
            $('#datatable-keytable').DataTable({keys: true});
            $('#datatable-responsive').DataTable();
            $('#datatable-colvid').DataTable({
                "dom": 'C<"clear">lfrtip',
                "colVis": {
                    "buttonText": "Change columns"
                }
            });
            $('#datatable-scroller').DataTable({
                ajax: "/admin/plugins/datatables/json/scroller-demo.json",
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

 </body>
</html>

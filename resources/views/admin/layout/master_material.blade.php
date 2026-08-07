<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="{{env('APP_NAME')}} - @yield('title')">
        <meta name="author" content="{{Session::get('software_title')}} - @yield('title')">

        <!-- App favicon -->
        <link rel="shortcut icon" href="/admin/assets/images/favicon.ico">
        <!-- App title -->
        <title>@yield('title') - {{env('APP_NAME')}}</title>

        <!--Morris Chart CSS -->
		<link rel="stylesheet" href="/admin/plugins/morris/morris.css">

        <!-- App css (Zircos material-design variant) -->
        <link href="/admin/assets/css/bootstrap.min.css?v={{ (file_exists(public_path('admin/assets/css/bootstrap.min.css')) ? filemtime(public_path('admin/assets/css/bootstrap.min.css')) : time()) }}" rel="stylesheet" type="text/css" />
        <link href="/admin/assets/css/core-material.css?v={{ (file_exists(public_path('admin/assets/css/core-material.css')) ? filemtime(public_path('admin/assets/css/core-material.css')) : time()) }}" rel="stylesheet" type="text/css" />
        <link href="/admin/assets/css/components-material.css?v={{ (file_exists(public_path('admin/assets/css/components-material.css')) ? filemtime(public_path('admin/assets/css/components-material.css')) : time()) }}" rel="stylesheet" type="text/css" />
        <link href="/admin/assets/css/icons.css?v={{ (file_exists(public_path('admin/assets/css/icons.css')) ? filemtime(public_path('admin/assets/css/icons.css')) : time()) }}" rel="stylesheet" type="text/css" />
        <link href="/admin/assets/css/pages-material.css?v={{ (file_exists(public_path('admin/assets/css/pages-material.css')) ? filemtime(public_path('admin/assets/css/pages-material.css')) : time()) }}" rel="stylesheet" type="text/css" />
        <link href="/admin/assets/css/menu-material.css?v={{ (file_exists(public_path('admin/assets/css/menu-material.css')) ? filemtime(public_path('admin/assets/css/menu-material.css')) : time()) }}" rel="stylesheet" type="text/css" />
        <link href="/admin/assets/css/responsive-material.css?v={{ (file_exists(public_path('admin/assets/css/responsive-material.css')) ? filemtime(public_path('admin/assets/css/responsive-material.css')) : time()) }}" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="/admin/plugins/switchery/switchery.min.css">

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <script src="https://cdn.ckeditor.com/ckeditor5/20.0.0/classic/ckeditor.js"></script>

        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
{{--  <link rel="stylesheet" href="//resources/demos/style.css">--}}

        <script src="/admin/assets/js/modernizr.min.js"></script>
        <style type="text/css">
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
          <style type="text/css">
  .error
  {
      border: 1px solid red !important;
  }
  .help-block
  {
      color: red !important;
  }

  .form-control:focus {
      outline: none !important;
      border:1px solid blue !important;
      box-shadow: 0 0 10px #719ECE !important;
  }
  .js-example-basic-single:focus{
      outline: none !important;
      border:1px solid red !important;
      box-shadow: 0 0 10px #719ECE !important;
  }
  input[type="date"].form-control, input[type="time"].form-control, input[type="datetime-local"].form-control, input[type="month"].form-control {
      line-height: 24px !important;
  }
  .select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 0 !important;
    border-radius: 4px;
    border-bottom: 1px solid !important;
}

      </style>
        <style>
            .discountTotal {
                margin-top: 30px;
            }
        </style>
    </head>


    <body class="fixed-left">
       @include("admin.layout.menu")
            @yield('content')


                <footer class="footer text-right">
                   2020 {{Session::get('software_title')}}
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



    </body>

    <script>
        var resizefunc = [];
    </script>
    <style>

    </style>
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

    <!-- Counter js  -->
    <script src="/admin/plugins/waypoints/jquery.waypoints.min.js"></script>
    <script src="/admin/plugins/counterup/jquery.counterup.min.js"></script>

    <!--Morris Chart-->
    {{--		<script src="/admin/plugins/morris/morris.min.js"></script>--}}
    <script src="/admin/plugins/raphael/raphael-min.js"></script>

    <!-- Dashboard init -->
    {{--        <script src="/admin/assets/pages/jquery.dashboard.js')}}"></script>--}}

    <!-- App js -->
    <script src="/admin/assets/js/jquery.core.js"></script>
    <script src="/admin/assets/js/jquery.app.js"></script>
    <script src="/admin/select2.min.js" defer></script>
    <link href="/admin/select2.min.css" rel="stylesheet" />

    <script type="text/javascript">
        $(document).ready(function() {
            $('.js-example-basic-single').select2();
        });

        $( "#quot_date" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'mm/dd/yy',
            onClose: function(){
                getdate($(this).val());
            }
        });
        $( "#valid_until" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-mm-yy'
        });

$(document).ready(function() {
  $(document).on('focus', ':input', function() {
    $(this).attr('autocomplete', 'off');
  });
});
    </script>
@yield('import-javascript')
</html>

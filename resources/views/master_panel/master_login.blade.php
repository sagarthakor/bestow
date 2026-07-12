<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('public/default/assets/images/favicon.ico')}}">
        <!-- App title -->
        <title>Zircos - Responsive Admin Dashboard Template</title>

        <!-- App css -->
        <link href="{{asset('public/adminpanel/default/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/adminpanel/default/assets/css/core.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/adminpanel/default/assets/css/components.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/adminpanel/default/assets/css/icons.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/adminpanel/default/assets/css/pages.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/adminpanel/default/assets/css/menu.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('public/adminpanel/default/assets/css/responsive.css')}}" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="{{asset('public/default/assets/js/modernizr.min.js')}}"></script>

    </head>


    <body class="bg-transparent">

        <!-- HOME -->
        <section>
            <div class="container-alt">
                <div class="row">
                    <div class="col-sm-12">

                        <div class="wrapper-page">

                            <div class="m-t-40 account-pages">
                                <div class="text-center account-logo-box">
                                    <h2 class="text-uppercase">
                                        <a href="index.html" class="text-success">
                                            <span><img src="{{asset('public/default/assets/images/logo.png')}}" alt="" height="36"></span>
                                        </a>
                                    </h2>
                                    <!--<h4 class="text-uppercase font-bold m-b-0">Sign In</h4>-->
                                </div>
                                <div class="account-content">
                                    @if(session()->has('message'))
                                        <div class="alert alert-danger">
                                            <strong>{{session()->get('message')}}</strong>
                                        </div> 
                                    @endif
                                    {{Form::open(['method'=>'post','route'=>'master.login_master','class'=>'form-horizontal'])}}
                                
                                        <div class="form-group ">
                                            <div class="col-xs-12">
                                                <input class="form-control" type="text" required="" name="username" placeholder="Username">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-xs-12">
                                                <input class="form-control" type="password" required="" name="password" placeholder="Password">
                                            </div>
                                        </div>

                                       

                                        <div class="form-group account-btn text-center m-t-10">
                                            <div class="col-xs-12">
                                                <button class="btn w-md btn-bordered btn-danger waves-effect waves-light" type="submit">Log In</button>
                                            </div>
                                        </div>

                                    {{Form::close()}}

                                    <div class="clearfix"></div>

                                </div>
                            </div>
                            <!-- end card-box-->



                        </div>
                        <!-- end wrapper -->

                    </div>
                </div>
            </div>
          </section>
          <!-- END HOME -->

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

        <!-- App js -->
        <script src="{{asset('public/adminpanel/default/assets/js/jquery.core.js')}}"></script>
        <script src="{{asset('public/adminpanel/default/assets/js/jquery.app.js')}}"></script>

    </body>
</html>
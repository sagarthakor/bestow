<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

    <!-- App favicon -->
    <link rel="shortcut icon" href="/default/assets/images/favicon.ico">
    <!-- App title -->
    <title>Login - Admin Panel</title>

    <!-- App css -->
    <link href="/adminpanel/default/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/adminpanel/default/assets/css/core.css" rel="stylesheet" type="text/css" />
    <link href="/adminpanel/default/assets/css/components.css" rel="stylesheet" type="text/css" />
    <link href="/adminpanel/default/assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="/adminpanel/default/assets/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="/adminpanel/default/assets/css/menu.css" rel="stylesheet" type="text/css" />
    <link href="/adminpanel/default/assets/css/responsive.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <script src="/default/assets/js/modernizr.min.js"></script>

    <style>
        .field-icon {
            float: right;
            margin-left: -25px;
            margin-top: -25px;
            position: relative;
            z-index: 2;
        }

    </style>
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
                                    <span><img src="/default/assets/images/logo.png" alt="" height="36"></span>
                                </a>
                            </h2>
                            <!--<h4 class="text-uppercase font-bold m-b-0">Sign In</h4>-->
                        </div>
                        <div class="account-content">
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            @if(session()->has('message'))
                                <div class="alert alert-danger">
                                    <strong>{{session()->get('message')}}</strong>
                                </div>
                            @endif
                            {{Form::open(['method'=>'post','route'=>'user.login','class'=>'form-horizontal'])}}


                            <div class="form-group ">
                                <div class="col-xs-12">
                                    <input class="form-control" type="text" required="" name="username" placeholder="Username">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-xs-12">
                                    <input id="password-field" type="password" class="form-control" placeholder="Password" name="password">
                                    <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                </div>

                            </div>
                            <div class="form-group ">
                                <div class="col-xs-12">
                                    <select class="form-control {{$errors->first('finacial_year')}}, ' error" name="finacial_year">
                                        <option value="">Select Finacial Year</option>
                                        @foreach($finacial_year as $year)
                                            <option value="{{$year->id}}">{{$year->finacial_year}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('finacial_year'))
                                        <p class="help-block">{{ $errors->first('finacial_year') }}</p>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-xs-12">
                                    <a style="cursor: pointer" href="{{url('user/password/forgot')}}">Forgotten password?</a>
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
<script src="/adminpanel/default/assets/js/jquery.min.js"></script>
<script src="/adminpanel/default/assets/js/bootstrap.min.js"></script>
<script src="/adminpanel/default/assets/js/detect.js"></script>
<script src="/adminpanel/default/assets/js/fastclick.js"></script>
<script src="/adminpanel/default/assets/js/jquery.blockUI.js"></script>
<script src="/adminpanel/default/assets/js/waves.js"></script>
<script src="/adminpanel/default/assets/js/jquery.slimscroll.js"></script>
<script src="/adminpanel/default/assets/js/jquery.scrollTo.min.js"></script>

<!-- App js -->
<script src="/adminpanel/default/assets/js/jquery.core.js"></script>
<script src="/adminpanel/default/assets/js/jquery.app.js"></script>
<script>
    $(".toggle-password").click(function() {

        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
</script>
</body>
</html>

@extends('admin.layout.master')

@section('title', 'Customer Preview')

@section('sidebar')
    @parent

@endsection

@section('content')

    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">


                <div class="row">
                    <div class="col-xs-12">
                        <div class="page-title-box">
                            <h4 class="page-title">
                                Batch wise Production Planning
                            </h4>
                            <ol class="breadcrumb p-0 m-0">
                                <li>
                                    <a href="#">{{Session::get('software_title')}}</a>
                                </li>
                                {{--                                <li class="active">--}}
                                {{--                                    <a href="#">Machine List </a>--}}
                                {{--                                </li>--}}
                                @can('production_create')
                                    <li style="text-align: right;margin-bottom: 5px">
                                        <a class="btn btn-primary" href="{{url('production/machine/list')}}">Add Batch</a>
                                    </li>
                                @endcan
                            </ol>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
                @if(session()->has("message"))
                    <div class="row">
                        <div class="col-md-12 alert alert-success">
                            <strong>{{Session::get("message")}}</strong>
                        </div>
                    </div>
                @endif
                <div class="row text-center">
                    <div class="col-xs-12">
                        <div class="">
                            @foreach($machine as $mmlist)
                                <div class="col-lg-4 col-md-4 col-sm-6">
                                    <a href="{{url('production/complete')}}">
                                        <div class="card-box widget-box-one bg-secondary">
                                            <div class="wigdet-one-content">
                                                <p class="m-0 text-uppercase font-600 font-secondary text-overflow text-dark">{{$mmlist->machine_name}} </p>
                                                <?php
                                                $i=0;
                                                ?>
                                                @foreach($production as $pp)
                                                    @if($pp->machine == $mmlist->id and $pp->production_status ==null)
                                                        <?php
                                                        $i++;
                                                        ?>
                                                    @endif

                                                @endforeach
                                                <h1 class="text-dark"><span><?=$i?></span></h1>
                                                <!--                                        <p class="text-muted m-0"><b>Last:</b> 30.4k</p>-->
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- content -->

    <style>
        #myImg {
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        #myImg:hover {opacity: 0.7;}

        /* The Modal (background) */
        .modal {
            display Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            padding-top: 100px; /* Location of the box */
            left: 0;
            top: 0;
            widt: none; /*h: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            /*background-color: rgb(0,0,0); !* Fallback color *!*/
            /*background-color: rgba(0,0,0,0.9); !* Black w/ opacity *!*/
        }

        /* Modal Content (image) */
        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        /* Caption of Modal Image */
        #caption {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            text-align: center;
            color: #ccc;
            padding: 10px 0;
            height: 150px;
        }

        /* Add Animation */
        .modal-content, #caption {
            -webkit-animation-name: zoom;
            -webkit-animation-duration: 0.6s;
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        @-webkit-keyframes zoom {
            from {-webkit-transform:scale(0)}
            to {-webkit-transform:scale(1)}
        }

        @keyframes zoom {
            from {transform:scale(0)}
            to {transform:scale(1)}
        }

        /* The Close Button */
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        /* 100% Image Width on Smaller Screens */
        @media only screen and (max-width: 700px){
            .modal-content {
                width: 100%;
            }
        }
    </style>

    <script src="{{asset('/admin/assets/js/jquery.min.js')}}"></script>
    <script type="text/javascript">
        // Using jQuery.
        $(document).ready(function () {

            $('.photo').click(function() {

                var path = $(this).attr('src');
                var id=$(this).attr("data-id");
                $("#myModal_"+id).show();
                $("#popup_img"+id).attr("src",path);
                //captionText.innerHTML = this.alt;
                //alert(id);
            });

            $('.js-example-basic-single').select2();

            $('.photo').click(function() {

                var path = $(this).attr('src');
                var id=$(this).attr("data-id");
                $("#myModal_"+id).show();
                $("#popup_img"+id).attr("src",path);
                //captionText.innerHTML = this.alt;
                //alert(id);
            });

        });
        function closmodal(machine)
        {
            $("#myModal_"+machine).hide();
        }
        $(function() {
            $('form').each(function() {
                $(this).find('input').keypress(function(e) {
                    // Enter pressed?
                    if(e.which == 10 || e.which == 13) {
                        this.form.submit();
                    }
                });

                $(this).find('input[type=submit]').hide();
            });
        });
        function getmaterial(sizes,machine)
        {
            var nos=$("#nos_"+machine).val();
            var size=$("#size_"+machine).val();
            var appurl="{{url('/')}}";
            $.ajax({
                url:appurl+'/getproduction_mat',
                data:{size:size,machine:machine,nos:nos,'_token':"{{csrf_token()}}"},
                method:'post',
                dataType:'json',
                success:function (response)
                {
                    $("#required_qty_"+machine).val(response['required_qty']);
                    $("#material_div_"+machine).html(response['item']);
                }
            });
        }

        function getrawmaterial(ele)
        {
            var material=$(ele).closest('tr').find('.required_mat').val();
            alert(material);
        }
        function getimage(product,machine)
        {
            var appurl="{{url('/')}}";
            $.ajax({
                url:appurl+'/getproduct_image',
                data:{product:product},
                method: 'get',
                dataType: 'json',
                success:function (res)
                {
                    $(".img_"+machine).attr("src","{{asset('public/product_image/')}}/"+res['product_image']);
                    $(".img_"+machine).attr('width', "50px");
                    $(".img_"+machine).attr('height', "80px");
                }
            });
        }
    </script>
    <script>
        function show_image(machine)
        {
            alert(machine);
            alert(this.src);
        }

    </script>

@endsection

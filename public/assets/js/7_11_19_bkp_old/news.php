<?php include("acess_admin/include/connection.php");?>
<!DOCTYPE html>

<html lang="en">

<?php include('include/headerscript.php'); ?>

<style type="text/css">
     .featured-item .title h4 {
   margin-bottom: 20px;
    font-size: 17px !important;
    /* letter-spacing: 1px; */
    color: #1b2b40;
    font-weight: bold;
    font-weight: 700;
}
.breadcrumb>li+li:before {
    padding: 0 5px;
    color: #d8dde2;
    content: "/\00a0";
}
   </style>

<body>





    <div class="wrapper">



        <?php include('include/header.php'); ?>



        <!--page title start-->

        <section class="page-title">

            <div class="container">

                <div class="row">

                    <div class="col-md-12">

                        <h4 class="text-uppercase text-white">Latest Blog</h4>

                        <ol class="breadcrumb">

                            <li><a href="#">Home</a>

                            </li>

                            <li class="active">Latest Blog</li>

                        </ol>

                    </div>

                </div>

            </div>

        </section>

        <!--page title end-->

 <!--body content start-->

              <!--body content start-->

        <section class="body-content ">

<?php

         $sql = "SELECT * FROM `blogs`";
         $blogs = mysqli_query($con,$sql);
         $i = 0;
         while($blogs_result = mysqli_fetch_assoc($blogs))
         {  
            $i++;
            //echo $blogs_result['postImg'];
?>

            <div class="page-content pt-50">

                <div class="container">

                    <div class="row">

                        <div class="post-list-aside ">

                            <div class="post-single box_card_product">

                                <div class="col-md-5">

                                    <div class="post-img">


                                        <img src="acess_admin/assets/images/blog/<?php echo $blogs_result['postImg'];?>" alt="">

                                    </div>

                                </div>

                                <div class="col-md-7">

                                    <div class="post-desk">

                                        <h4 class="text-uppercase">

                                            <a href="#"><?php echo $blogs_result['postTitle'];?></a>

                                        </h4>

                                        <div class="date">

                                            <a href="#" class="author"><?php echo $blogs_result['postBy'];?></a>
                                                <?php echo $blogs_result['timestamps'];?>

                                        </div>

                                        <p>

                                           <?php echo $blogs_result['description'];?>

                                        </p>

                                        <!--a href="blog-detail.php" class="p-read-more">Read More <i class="icon-arrows_slim_right"></i></a-->

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
<?php }?>
        </section>


        <?php include('include/footer.php'); ?>

    </div>

    <?php include('include/footerscript.php'); ?>

</body>

</html>


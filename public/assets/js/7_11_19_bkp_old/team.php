
<!DOCTYPE html>
<html lang="en">
<?php include('include/headerscript.php'); ?>
<style>
    .team-member, .team-member .team-img {
    position: relative;
    border: 1px solid lightgray;
    padding: 10px;
}
.team-member .team-img img {
    width: 100%;
    height: 300px;
}
.team-member .team-intro {
    position: absolute;
    right: 0;
    bottom: 30px;
    width: 100%;
    padding: 10px 20px;
    text-align: right;
    background: rgba(0, 0, 0, .7);
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
                        <h4 class="text-uppercase"> Team Deltaweb</h4>
                        <ol class="breadcrumb">
                            <li><a href="#">Home</a>
                            </li>
                           
                            <li class="active">Team Member </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <!--page title end-->

       <section class="body-content ">
            <div class="page-content">
                <div class="container">
                    <div class="row p-t-60">
                        <div class="heading-title text-center">
                            <h3 class="text-uppercase">Deltaweb Solution Team Member </h3>
                           
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-4 col-sm-6">
                                <div class="our-team">
                                    <img src="assets/img/user1.png" alt="">
                                    <div class="team-content">
                                        <div class="team-info">
                                            <h3 class="title">Williamson</h3>
                                            <span class="post">Web Developer</span>
                                            <ul class="icon">
                                                <li><a href="#"><i class="fab fa-facebook"></i></a></li>
                                                <li><a href="#"><i class="fab fa-google-plus"></i></a></li>
                                                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                     
                            <div class="col-md-4 col-sm-6">
                                <div class="our-team">
                                    <img src="assets/img/user1.png" alt="">
                                    <div class="team-content">
                                        <div class="team-info">
                                            <h3 class="title">kristina</h3>
                                            <span class="post">Web Designer</span>
                                            <ul class="icon">
                                                <li><a href="#"><i class="fab fa-facebook"></i></a></li>
                                                <li><a href="#"><i class="fab fa-google-plus"></i></a></li>
                                                <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        </section>

        <?php include('include/footer.php'); ?>

    </div>

    <?php include('include/footerscript.php'); ?>
</body>
</html>

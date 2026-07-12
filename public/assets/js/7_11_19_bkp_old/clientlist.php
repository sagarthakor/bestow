
<!DOCTYPE html>
<html lang="en">

 <?php 
   include('include/headerscript.php'); 
   include('acess_admin/include/connection.php'); 
   ?>
   <style>
       .portfolio .portfolio-item .thumb img {
    display: block;
    width: 100%;
    height: 150px;
}

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
      .page-content {
   
     padding: 0px; 
     }
   </style>
<body>

   
 <div class="wrapper">

        <!--header start-->
         <?php include('include/header.php'); ?>
        <!--header end-->
<section class="page-title">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="text-uppercase text-white"> Client List</h4>
                        <ol class="breadcrumb">
                            <li><a href="#">Home</a>
                            </li>
                            <li class="active">Client List</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
    
      
     <section class="body-content">
            <div class="page-content">
                
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portfolio col-8 gutter " style="position: relative; height: 691.689px;">

  <?php 
                           $get = mysqli_query($con,"SELECT * FROM clientlogo ORDER BY id DESC");
                           if(mysqli_num_rows($get)>0){
                              $i = 1;
                              while ($row = mysqli_fetch_assoc($get)) {
                                          
                           ?>
                                <div class="portfolio-item " style="position: absolute; left: 0px; top: 0px;border: 1px solid whitesmoke;">
                                    <div class="thumb">
                                        <a href="#">
                                            <img src="<?php echo user_image_view.'clientlogo/'.$row['image']; ?>" alt="<?php echo $row['alt']; ?>">
                                        </a>
                                    </div>
                                </div>
                               
 <?php
                              }
                           }
                           ?>
                              

                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </section>
      
      

        <!--footer start 1-->
       <?php include('include/footer.php'); ?>
        <!--footer 1 end-->

    </div>



      <?php include('include/footerscript.php'); ?>
</body>
</html>

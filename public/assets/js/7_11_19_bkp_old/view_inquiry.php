<!DOCTYPE html>
<html>
   <?php require_once('include/headerscript.php'); ?>
   <body class="fixed-left">
      <!-- Begin page -->
      <div id="wrapper">
         <!-- Top Bar Start -->
         <?php require_once('include/topbar.php'); ?>
         <!-- Top Bar End -->
         <!-- ========== Left Sidebar Start ========== -->
         <?php require_once('include/sidebar.php'); ?>
         <!-- Left Sidebar End -->
         <!-- ============================================================== -->
         <!-- Start Page Content here -->
         <!-- ============================================================== -->
         <div class="content-page">
            <!-- Start content -->
            <div class="content">
               <div class="container">
                   <div class="row">
                     <div class="col-md-6">
                        <div class="page-title-box">
                           <h4 class="page-title">View Customeer List</h4>
                           <div class="clearfix"></div>
                        </div>
                     </div>
                      <div class="col-md-6" align="right">
                        <a href="add_customer.php">
                        <button class="btn btn-inverse waves-effect waves-light m-b-5"> <i class="fa fa-plus m-r-5"></i> <span>Add Customeer List</span> </button>
                        </a>
                     </div>
                  </div>
               
                  <div class="row">
                     <div class="col-sm-12">
                        <div class="card-box table-responsive">
                           <table id="datatable" class="table table-striped table-bordered">
                              <thead>
                                 <tr>
                                    <th>Sr No</th>
                                    <th>Name</th>
                                    <th>Mobile No</th>
                                    <th>Email</th>
                                    <th>Spouse Name</th>
                                    <th>Action</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr>
                                    <td>1</td>
                                    <td>System Architect</td>
                                    <td>Edinburgh</td>
                                    <td>Edinburgh</td>
                                    <td>Edinburgh</td>
                                    <td>
                                       <a href="#" title="Edit">
                                       <i class="fa fa-edit" style="font-size: 20px;"></i>
                                       </a>
                                       <a href="#" title="Delete">
                                       <i class="fa fa-trash-o" style="font-size: 20px;"></i>
                                       </a>
                                       <a href="#" title="View">
                                       <i class="fa fa-eye" style="font-size: 20px;"></i>
                                       </a>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- container -->
            </div>
            <!-- content -->
         </div>
         <!-- ============================================================== -->
         <!-- End of the page -->
         <!-- ============================================================== -->
      </div>
      <!-- END wrapper -->
      <!-- START Footerscript -->
      <?php require_once('include/footerscript.php'); ?>
   </body>
</html>
 @extends('includes.master')

@section('title')

@section('content')
 <div class="container">
        
         <div class="shop-area">
            <div class="breadcrumb-area">
               <ul>
                  <li><a href="{{url('index')}}">Home</a></li>
                  <li><span>Shop</span></li>
               </ul>
            </div>
            <div class="row">
               <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                  @include('includes/sidebar')
               </div>
               <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12">
                  <div class="shop-left-sidebar">
                     <div class="shop-products">
                        <div class="filter-bar">
                           <div class="row">
                              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                 <div class="prd-tab">
                                    <ul class="nav nav-tabs" id="myTab1" role="tablist">
                                       <li class="nav-item">
                                          <a class="nav-link active p-3" id="tab-2" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab2" aria-selected="false">All</a>
                                       </li>
                                       <li class="nav-item">
                                          <a class="nav-link " id="tab-1" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab1" aria-selected="true"><i class="fas fa-list-ul"></i></a>
                                       </li>
                                    </ul>
                                 </div>
                                 <div class="product-filter">
                                    <form action="#">
                                       <select name="pro-filter" id="pro-filter">
                                          <option value="1">Default Sorting </option>
                                          <option value="3">New Product </option>
                                          <option value="2">Top Sales </option>
                                       </select>
                                    </form>
                                 </div>
                              </div>
                              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                 <div class="product-showing">
                                    <p>SHOWING 1–9 OF 32 RESULTS </p>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="tab-content" id="myTabContent-1">
                           <div class="tab-pane fade show active" id="tab2" role="tabpanel" aria-labelledby="tab-2">
                              <div class="shop-grid">
                                 <div class="product-item">
                                    <Ul>
                                       <div class="row">
                                          <div class="col-md-4">
                                             <li>
                                                <div class="product-grid">
                                                <div class="product-image">
                                                   <a href="view.php">
                                                   <img class="pic-1" src="{{asset('public/assets/images/product/ts-1.jpg')}}" alt="Product image">
                                                   </a>
                                                   <ul class="social">
                                                      <li><a href="view.php" data-tip="Quick View"><i class="fa fa-eye"></i></a></li>
                                                      <li><a href="wishlist.php" data-tip="Wishlist"><i class="fa fa-shopping-bag"></i></a></li>
                                                   </ul>
                                                </div>
                                                <h3 class="title"></h3>
                                                <div class="product-content">
                                                   <div class="price">
                                                      <span></span>
                                                   </div>
                                                   <a class="add-to-cart" href="view.php">See More</a>
                                                </div>
                                                </div>
                                             </li>
                                          </div>
                                          <div class="col-md-4">
                                             <li>
                                                <div class="product-grid">
                                                <div class="product-image">
                                                   <a href="view.php">
                                                   <img class="pic-1" src="{{asset('public/assets/images/product/ts-2.jpg')}}" alt="Product image">
                                                   </a> 
                                                   <ul class="social">
                                                      <li><a href="view.php" data-tip="Quick View"><i class="fa fa-eye"></i></a></li>
                                                      <li><a href="wishlist.php" data-tip="Wishlist"><i class="fa fa-shopping-bag"></i></a></li>
                                                   </ul>
                                                </div>
                                                <h3 class="title"></h3>
                                                <div class="product-content">
                                                   <div class="price">
                                                      <span></span>
                                                   </div>
                                                   <a class="add-to-cart" href="view.php">See More</a>
                                                </div>
                                                </div>
                                             </li>
                                          </div>
                                          <div class="col-md-4">
                                             <li>
                                                <div class="product-grid">
                                                <div class="product-image">
                                                   <a href="view.php">
                                                   <img class="pic-1" src="{{asset('public/assets/images/product/ts-3.jpg')}}" alt="Product image">
                                                   </a>
                                                   <ul class="social">
                                                      <li><a href="view.php" data-tip="Quick View"><i class="fa fa-eye"></i></a></li>
                                                      <li><a href="wishlist.php" data-tip="Wishlist"><i class="fa fa-shopping-bag"></i></a></li>
                                                   </ul>
                                                </div>
                                                <h3 class="title"></h3>
                                                <div class="product-content">
                                                   <div class="price">
                                                      <span></span>
                                                   </div>
                                                   <a class="add-to-cart" href="view.php">See More</a>
                                                </div>
                                                </div>
                                             </li>
                                          </div>
                                       </div>
                                    </Ul>

                                    <Ul>
                                       <div class="row mt-4">
                                          <div class="col-md-4">
                                             <li>
                                                <div class="product-grid">
                                                <div class="product-image">
                                                   <a href="view.php">
                                                   <img class="pic-1" src="{{asset('public/assets/images/product/ts-2.jpg')}}" alt="Product image">
                                                   </a>
                                                   <ul class="social">
                                                      <li><a href="view.php" data-tip="Quick View"><i class="fa fa-eye"></i></a></li>
                                                      <li><a href="wishlist.php" data-tip="Wishlist"><i class="fa fa-shopping-bag"></i></a></li>
                                                   </ul>
                                                </div>
                                                <h3 class="title"></h3>
                                                <div class="product-content">
                                                   <div class="price">
                                                      <span></span>
                                                   </div>
                                                   <a class="add-to-cart" href="view.php">See More</a>
                                                </div>
                                                </div>
                                             </li>
                                          </div>
                                          <div class="col-md-4">
                                             <li>
                                                <div class="product-grid">
                                                <div class="product-image">
                                                   <a href="view.php">
                                                   <img class="pic-1" src="{{asset('public/assets/images/product/ts-3.jpg')}}" alt="Product image">
                                                   </a>
                                                   <ul class="social">
                                                      <li><a href="view.php" data-tip="Quick View"><i class="fa fa-eye"></i></a></li>
                                                      <li><a href="wishlist.php" data-tip="Wishlist"><i class="fa fa-shopping-bag"></i></a></li>
                                                   </ul>
                                                </div>
                                                <h3 class="title"></h3>
                                                <div class="product-content">
                                                   <div class="price">
                                                      <span></span>
                                                   </div>
                                                   <a class="add-to-cart" href="view.php">See More</a>
                                                </div>
                                                </div>
                                             </li>
                                          </div>
                                          <div class="col-md-4">
                                             <li>
                                                <div class="product-grid">
                                                <div class="product-image">
                                                   <a href="view.php">
                                                   <img class="pic-1" src="{{asset('public/assets/images/product/ts-1.jpg')}}" alt="Product image">
                                                   </a>
                                                   <ul class="social">
                                                      <li><a href="view.php" data-tip="Quick View"><i class="fa fa-eye"></i></a></li>
                                                      <li><a href="wishlist.php" data-tip="Wishlist"><i class="fa fa-shopping-bag"></i></a></li>
                                                   </ul>
                                                </div>
                                                <h3 class="title"></h3>
                                                <div class="product-content">
                                                   <div class="price">
                                                      <span></span>
                                                   </div>
                                                   <a class="add-to-cart" href="view.php">See More</a>
                                                </div>
                                                </div>
                                             </li>
                                          </div>
                                       </div>
                                    </Ul> 
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="pagination-area mt-5">
                        <nav aria-label="Page navigation example">
                           <ul class="pagination">
                              <li class="page-item">
                                 <a class="page-link" href="#" aria-label="Previous">
                                 <span aria-hidden="true">&laquo;</span>
                                 <span class="sr-only">Previous</span>
                                 </a>
                              </li>
                              <li class="page-item"><a class="page-link" href="javascript:;">1</a></li>
                              <li class="page-item"><a class="page-link" href="javascript:;">2</a></li>
                              <li class="page-item"><a class="page-link" href="javascript:;">3</a></li>
                              <li class="page-item">
                                 <a class="page-link" href="#" aria-label="Next">
                                 <span aria-hidden="true">&raquo;</span>
                                 <span class="sr-only">Next</span>
                                 </a>
                              </li>
                           </ul>
                        </nav>
                     </div>
                  </div>  
               </div>
            </div>
         </div>
      </div>
      @endsection
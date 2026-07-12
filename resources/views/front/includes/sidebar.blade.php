              <style>
                  .showcategory{
                      display: block;
                  }
              </style>
               <div class="sidebar-area">

                     <div class="shop-widget">
                        <div class="widget-title">
                           <h2>Category</h2>
                        </div>
                        <div class="widget-content">
                            <form method="get">
                           <ul id="accordion">
                               @foreach($category as $clist)
                              <li>
                                 <h4><a  href="#">{{$clist->category_name}}</a><span class="plusminus">+</span></h4>
                                 <ul class="showcategory">
                                     <?php
                                     $subrow=0;
                                     ?>
                                     @foreach($subcategory as $slist)
                                             <?php
                                             $subrow++;
                                             ?>
                                         @if($clist->id==$slist->category)

                                         <li>
                                             <a href="{{url('products/'.$clist->category_name.'/'.$slist->subcategory_name)}}">
                                                 {{$slist->subcategory_name}}
                                             </a>
                                             <span class="custom-control custom-checkbox">
                                                 <input onchange="filterSearch()" value="{{$slist->subcategory_name}}" type="checkbox" class="productDetail subcategory show custom-control-input" id="customCheck{{$subrow}}">
                                                 <label class="custom-control-label" for="customCheck{{$subrow}}"></label>
                                             </span>
                                         </li>
                                         @endif

                                     @endforeach
                                 </ul>
                              </li>
                               @endforeach

                           </ul>
                            </form>
                        </div>
                     </div>
                  </div>

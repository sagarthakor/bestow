@extends('front.includes.master')

@section('title')

@section('content')

    <div class="container">

        <div class="shop-area">
            <div class="breadcrumb-area">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><span>Shop</span></li>
                </ul>
            </div>
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12">
                    @include('front.includes/sidebar')
                </div>
                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12">
                    <div class="shop-left-sidebar">
                        <div class="shop-products">

                            <div class="tab-content" id="myTabContent-1">

                                <div class="tab-pane fade show active" id="tab2" role="tabpanel" aria-labelledby="tab-2">
                                    <div class="shop-grid">
                                        <div class="product-item">
                                            <Ul>
                                                <div class="row searchResult">
                                                    @forelse($product as $val)
                                                        @if($val->show_hide=="show")
                                                            <div class="col-md-4">
                                                                <li>
                                                                    <div class="product-grid">
                                                                        <div class="product-image">
                                                                            <a href="{{url('product-details/'.$val->category_name.'/'.$val->subcategory_name.'/'.$val->item_code)}}">
                                                                                <img class="pic-1" src="/product_image/{{$val->product_image}}" alt="Product image">
                                                                            </a>

                                                                        </div>
                                                                        <h3 class="title">{{$val->product_name}}</h3>
                                                                        <!--<div class="product-content">-->
                                                                        <!--    <div class="price">-->
                                                                        <!--        <span></span>-->
                                                                        <!--    </div>-->
                                                                        <!--    <a class="add-to-cart" href="{{url('product-details/'.$val->category_name.'/'.$val->subcategory_name.'/'.$val->item_code)}}">See More</a>-->
                                                                        <!--</div>-->
                                                                    </div>
                                                                </li>
                                                            </div>
                                                        @endif
                                                    @empty
                                                        <p>No Record found</p>
                                                    @endforelse


                                                </div>
                                            </Ul>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="/front/js/vendor/jquery-3.3.1.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            //filterSearch();
            $('.productDetail').change(function(){
                // alert("hh");
                filterSearch();
            });
            // $('#priceSlider').slider({
            // }).on('change', priceRange);
        });

        {{--function subcategory_search(category)--}}
        {{--{--}}
        {{--    var sub="<?=$_GET['subcategory'] ?? '';?>";--}}
        {{--    //alert(sub);--}}
        {{--    //alert(category);--}}
        {{--    var selected_value = []; // initialize empty array--}}
        {{--    $(".show:checked").each(function(){--}}
        {{--        selected_value.push($(this).val());--}}
        {{--    });--}}
        {{--    var appurl="{{url('/')}}";--}}
        {{--    //window.location=appurl+'/product-category/category/'+category+'/subcategory/'+selected_value;--}}
        {{--    console.log(selected_value); //Press F12 to see all selected values--}}
        {{--}--}}
        $(document).ready(function() {


        });


        function filterSearch() {
            $('.searchResult').html('<div id="loading">Loading .....</div>');
            var action = 'fetch_data';
            // var minPrice = $('#minPrice').val();
            // var maxPrice = $('#maxPrice').val();
            var subcategory = getFilterData('subcategory');
            var appurl="{{url('/')}}";
            //var ram = getFilterData('ram');
            //var storage = getFilterData('storage');
            $.ajax({
                url:appurl+"/product_filters",
                method:"post",
                data:{action:action,subcategory:subcategory,"_token":"{{csrf_token()}}"},
                success:function(data){
                    //alert(data);
                    $('.searchResult').html(data);
                }
            });
        }

        function getFilterData(className) {
            var filter = [];
            $('.'+className+':checked').each(function(){
                filter.push($(this).val());
            });
            return filter;
        }
    </script>

@endsection

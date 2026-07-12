@if($featurePromotion)
<section class="mb-4">
  <div class="container">
    <div class="px-2 py-4 px-md-4 py-md-3 bg-white shadow-sm rounded">
      <div class="d-flex mb-3 align-items-baseline border-bottom">
        <h3 class="h5 fw-700 mb-0">
          <span class="border-bottom border-primary border-width-2 pb-3 d-inline-block">@lang($featurePromotion->title)</span>
        </h3>
      </div>
      @foreach($featurePromotion->products->chunk(15) as  $productChunk) 
      <div class="aiz-carousel gutters-10 half-outside-arrow" data-items="6" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows='true'>
        @foreach($productChunk as $key => $product)
        <div class="carousel-box">
         @include('website.partials.product_box_1',['product' => $product])
       </div>
       @endforeach
     </div>
     @endforeach
   </div>
 </div>
</section> 
@endif

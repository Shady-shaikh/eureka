@extends('frontend.layouts.product')
@section('title','Product List')
@section('content')

<!-- filter menu for mobile view start -->
<div class="sticky-filter top-padding">
  <div class="one-fourth" id="filters">
    <button id="show-hidden-filter" class="sticky-filter-sort-btn w-100 triggerSidebar" type="button" name="button">Filters</button>
  </div>
  <div class="one-fourth" id="sortby">
    <button id="show-hidden-sort"  class="sticky-filter-sort-btn w-100 triggerSidebar1" type="button" name="button1">Sort By</button>
  </div>
</div>
<!-- filter menu for mobile view start -->
<!-- sort by section start -->
<div class="container-fluidcustom top-padding pt-5 ">
         <div class="row sortyby-section no-gutters sidebar-sortby">
            <div class="col-lg-10 col-sm-12 col-md-12 col-12 ">
            </div>
              <div class="col-lg-2 col-sm-12 col-md-12 col-12 sort-filter-section hidden-sort sidebar1">
                <a href="#" class="hideSidebar1"  ><i class="fa fa-times" aria-hidden="true"></i></a>

                <div class="center">
                  <select name="sources" id="sources" class="custom-select sources " placeholder="Recommended">
                    <option value="new-arrivals">New Arrivals</option>
                    <option value="popularity">Popularity</option>
                    <option value="highest-discount">Highest Discount</option>
                    <option value="low-to-high"> Price: Low to High</option>
                    <option value="high-to-low"> Price: High to Low</option>
                  </select>
                </div>
              <span class="sort-by ">Sort By :</span>
              </div>
         </div>
      </div>
<!-- sort by section end -->

    <!-- Product images section start -->
      <div class="container-fluidcustom ">
        <div class="row product-filter-section sidebar-filter">
          <!-- filter section start -->
          <div class="col-lg-2 filter-section hidden-filter pb-2 sidebar" >
            <a href="#" class="hideSidebar"><i class="fa fa-times" aria-hidden="true"></i></a>
            <div class="filter-section-style" >
              <div class="filter-section-div" style="">
                <div class="row ">
                <div class="col-lg-6 col-md-6 col-sm-6 col-6 m-auto">
                  <div class="filter-h4">
                    <h4>FILTERS</h4>
                  </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                  <button class="reset-btn " type="button" name="button"><span class="btn-text">RESET</span></button>
                </div>
              </div>
                 <hr class="filter-hr">

                 <div class="row pt-2">
                   <div class="col-lg-12 col-md-12 col-sm-12 col-12 m-auto">
                     <div class="style-checkbox">
                       <label class="style"><span class="gender-bold checkbox-text">Men</span><input type="checkbox" name="skill" value="female"><span class="checkmark"></span></label>
                       <label class="style"><span class="gender-bold checkbox-text">Women</span><input type="checkbox" name="skill" value="male"><span class="checkmark"></span></label>
                     </div>
                   </div>
                 </div>
                 @if(isset($filters) && count($filters)>0)
                   @foreach($filters as $filter)
                    @if($filter->filter_type == 'default')
                       <hr class="filter-hr">

                       <div class="row  ">
                         <div class="col-lg-12 col-md-12 col-sm-12 col-12 m-auto">
                             <!-- <div> -->
                                 <h4 class="filter-head">{{ $filter->filter_name }}<i class="fa fa-chevron-down float-right" aria-hidden="true"></i></h4>
                             <!-- </div> -->
                           <div class="style-checkbox">
                             @if(isset($filter->filtervalues) && count($filter->filtervalues)>0)
                               @foreach($filter->filtervalues as $filter_value)
                                 <label class="style"><span class="checkbox-text">{{ $filter_value->filter_value }}</span><input id="label1" type="checkbox" name="skill" value=""><span class="checkmark"></span></label>
                               @endforeach
                             @endif
                           </div>
                         </div>
                       </div>

                    @elseif($filter->filter_type == 'size')
                       <hr class="filter-hr">

                       <div class="row products-filters ">
                         <div class="col-lg-12 col-md-12 col-sm-12 col-12 m-auto">
                           <h4 class="filter-head">{{ $filter->filter_name }}</h4>
                           <ul class="ks-cboxtags">
                             @if(isset($filter->filtervalues) && count($filter->filtervalues)>0)
                               @foreach($filter->filtervalues as $filter_value)
                                <li>
                                  <input type="checkbox" id="size_filter_{{ $filter_value->filter_value_id }}" value="{{ $filter_value->filter_value_id }}">
                                  <label for="size_filter_{{ $filter_value->filter_value_id }}">{{ $filter_value->filter_value }}</label>
                                </li>
                               @endforeach
                             @endif
                           </ul>
                         </div>
                       </div>
                    @elseif($filter->filter_type == 'color')
                       <hr class="filter-hr">

                       <div class="row products-filters pt-3 ">
                         <div class="col-lg-12 col-md-12 col-sm-12 col-12 m-auto">
                           <!-- <h4 class="filter-head">COLOR<i class="fa fa-chevron-down float-right" aria-hidden="true"></i></h4>
                           @if(isset($filter->filtervalues) && count($filter->filtervalues)>0)
                             <div>
                               @foreach($filter->filtervalues as $filter_value)
                                 <a class="color-a" href="#"><input style="background:{{ $filter_value->filter_value }}; border:1px solid #eaeaea;" type="checkbox" name="" value="" class="color"></a>
                               @endforeach
                             </div>
                           @endif -->




                           <h4 class="filter-head">COLOR<i class="fa fa-chevron-down float-right" aria-hidden="true"></i></h4>
 @if(isset($filter->filtervalues) && count($filter->filtervalues)>0)
                  <div class="swatches-colors">
                    <div class="swatch-color clearfix" data-option-index="1">
                      @foreach($filter->filtervalues as $filter_value)
                      <div data-value="Blue" class="swatch-element color blue available">
                        <input style="background:{{ $filter_value->filter_value }};"  id="color_filter_{{ $filter_value->filter_value_id }}" type="checkbox" name="color[{{ $filter_value->filter_value_id }}]" value="{{ $filter_value->filter_value_id }}"   />
                        <label for="color_filter_{{ $filter_value->filter_value_id }}" >
                          <span style="background-color: {{ $filter_value->filter_value }};"></span>
                        </label>
                      </div>
                       @endforeach
                      
                    </div>
                    </div>
   @endif
                           <div class="more-color">
                             <a id="moreColor" href="#">View More</a>
                           </div>
                         </div>
                       </div>

                     @elseif($filter->filter_type == 'price')
                       <hr class="filter-hr">

                       <div class="row ">
                         <div class="col-lg-12 col-md-12 col-sm-12 col-12 m-auto">
                           <h4 class="filter-head">PRICE<i class="fa fa-chevron-down float-right" aria-hidden="true"></i></h4>
                           @if(isset($filter->filtervalues) && count($filter->filtervalues)>0)
                             <div class="scroll style-checkbox">
                               @foreach($filter->filtervalues as $filter_value)
                                 <label class="style"><span class="checkbox-text">{{ $filter_value->filter_value }}</span><input id="price1" type="checkbox" name="skill" value="female"><span class="checkmark"></span></label>
                               @endforeach
                             </div>
                           @endif
                         </div>
                       </div>
                       <div class="row products-filters pt-3 ">
                         <div class="col-sm-12">
                               <div id="slider-range"></div>
                             </div>
                           </div>
                           <div class="row slider-labels">
                             <div class="col-xs-6 caption">
                               <span id="slider-range-value1"></span>
                             </div>
                             <div class="col-xs-6 text-right caption">
                               <span id="slider-range-value2"></span>
                             </div>
                           </div>
                           <div class="row">
                             <div class="col-sm-12">
                               <form>
                                 <input type="hidden" name="min-value" value="">
                                 <input type="hidden" name="max-value" value="">
                               </form>
                             </div>
                       </div>
                       <div class="row text-center pt-3">
                         <div class="col-lg-12">
                             <div class="select-max-min">
                                 <input type="text" maxlength="9" id="low-price" placeholder="Min." name="high-price" class="min-max" aria-label="Min">
                                 <input type="text" maxlength="9" id="high-price" placeholder="Max." name="high-price" class="min-max" aria-label="Max">
                                 <button class="move-right"><span><i class="fa fa-chevron-right" aria-hidden="true"></i></span></button>
                             </div>
                         </div>
                       </div>


                     @endif

                     @endforeach
                   @endif

                   <div class="mobile-apply-section">
                      <hr class="filter-hr">
                      <div class="row ">
                        <div class="col-12 text-center">
                          <div class="mobile-apply-btn">
                            <a href="viewlist.blade.php">Apply</a>
                          </div>
                        </div>
                      </div>
                    </div>
                   </div>
                 </div>

               </div>
             <!-- </div> -->
           <!-- </div> -->
           <!-- filter section end -->

           <!-- product list section start -->
         <div class="col-lg-10 col-md-12 col-sm-12 col-12 product-list-images">
           <div class="row product-list-page ">
             @if(isset($products) && count($products)>0)
              @foreach($products as $product)
             <div class="col-lg-3 col-md-6 col-sm-6 col-6 content pb-4">
               <div class="product-hover img-hover1 card ">
                     <a href="{{ url('dp/') }}/{{ $product->category_slug }}/{{ $product->sub_category_slug }}/{{ $product->sub_sub_category_slug }}/{{ $product->product_slug }}"><img class="img-fluid image" src="{{ asset('public/backend-assets/uploads/product_images/') }}/{{ isset($product->product_images[0])?$product->product_images[0]->image_name:'' }}" alt="Img"></a>

                     <div class="pro-details pt-4 img-border-up">
                        <p class="pro-name"> {{ $product->product_title }} </p>
                        <p class="pro-style mb-1">{{ $product->product_sub_title }}</p>
                     </div>
                     <p class="pro-details pro-details-price">
                     <span class="price mr-2">₹ {{ $product->product_discounted_price }}</span>
                     <!-- <span class="mrp">MRP</span> -->
                     <span class="mrp-cut mr-2">₹ {{ $product->product_price }}</span>
                     <span class="discount">({{ $product->product_discount }}% OFF)</span>
                     </p>
                     <div class="overlay dn">
                       <a href="{{ url('dp/') }}/{{ $product->category_slug }}/{{ $product->sub_category_slug }}/{{ $product->sub_sub_category_slug }}/{{ $product->product_slug }}" target="_blank">
                       <div class="sliderdemo img-border-bottom">
                         @if(isset($product->product_images) && count($product->product_images)>0)
                          @foreach($product->product_images as $product_image)
                            <div><img class="img-fluid " src="{{ asset('public/backend-assets/uploads/product_images/') }}/{{ $product_image->image_name }}" alt="Img"></div>
                          @endforeach
                        @endif
                       </div>
                     </a>
                           <!-- Indicators -->
                           <div class="btn-whishlist-view">
                              <div class="row button-area">
                                <div class="col-6 pl-0 pr-1">
                                  <a class="whishlist-rborder d-block whishlist-color" href="{{ url('/addtowishlist') }}">WISHLIST</a>
                                </div>
                                <div class="col-6 pr-0 pl-1">
                                  <a class="similar-color d-block" href="{{ url('/similar') }}">SIMILAR</a>
                                </div>
                              </div>
                              <p class="pro-details pl-0">
                                <span class="price mr-2">₹ {{ isset($product->product_discounted_price)?$product->product_discounted_price:$product->product_price }}</span>
                                <!-- <span class="mrp">MRP</span> -->
                                @if(isset($product->product_discounted_price))<span class="mrp-cut mr-2">₹ {{ $product->product_price }}</span>@endif
                                @if(isset($product->product_discount))<span class="discount">({{ $product->product_discount }}% OFF)</span>@endif
                              </p>
                              <section class="size-product">
                                <p>
                                  <span class="font-bold">Sizes: </span>
                                  @if($product->product_type == 'simple')
                                    <span>{{ isset($product->size->size_name)?$product->size->size_name:'' }}</span>
                                  @elseif($product->product_type == 'configurable')
                                  @php
                                    $product_size_ids = array_unique(array_column($product->product_variants->toArray(), 'size_id'));
                                    //dd(array_unique($product_size_ids));
                                  @endphp
                                  <span>
                                    @foreach($product_size_ids as $product_size_id)
                                        {{ isset($size_list[$product_size_id])?$size_list[$product_size_id]:'' }}
                                    @endforeach
                                  </span>
                                  @endif
                                </p>
                                 <!-- <p><span class="font-bold">Sizes: </span><span>S, M, L, XL, 2XL, 3XL, 4XL, 5XL</span></p> -->
                              </section>
                           </div>
                     </div>
               </div>

               <div class="img-text">
                 <p><span>HAND</span>-PICKED</p>
               </div>
             </div>
           @endforeach
         @else
           <div class="col-lg-3 col-md-3 col-sm-3">
             <div class="product-hover img-hover1 card ">
              No Record Found
            </div>
          </div>
         @endif




           </div>

         <!-- </div> -->

           <!-- Pagination  -->
           <section>
             <div class="row pt-4 text-center">
               <div class="col-lg-12 col-md-12 col-sm-12 col-12 my-auto">
               <div class="page-bg">
               <ul class="pagination">
                <li class="pg-btn"><a href="#"><i class="fa fa-angle-left" aria-hidden="true"></i>  <span class="dn"> PREVIOUS</span></a></li>
                <li><a href="#">1</a></li>
                <li class="active"><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li><a href="#">6</a></li>
                <li class="pg-btn"><a href="#"><span class="dn">NEXT  </span><i class="fa fa-angle-right" aria-hidden="true"></i></a></li>
              </ul>
              </div>
             </div>
           </div>
           </section>


           <!-- Suggestbox -->
           <section class="py-5">
              <div class="row ">
                 <div class="col-lg-6">
                    <div class="suggest-head">
                       <p>Suggest us how we can improve more...</p>
                    </div>
                    <div class="text-suggest pb-4">
                       <textarea class="suggest-box w-100" name="name" rows="8"  placeholder="Your suggestions are very important for us. Please feel free to tell us what you want?"></textarea>
                    </div>
                    <button class="suggest-btn " type="button" name="button"><span class="btn-text">SUBMIT</span></button>
                 </div>
              </div>
           </section>
           <!-- Suggestbox end-->
         </div>
         <!-- product list section end -->
         </div>
      </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
      <script>
      $(document).ready(function()
      {
      $('.triggerSidebar').click(function() {
      // $('.sidebar').css('top', '160px')
      $('body').css('overflow', 'hidden')
      })
      var filter = function() {
      $('.sidebar').css('display', 'none')
      }
      $('.hideSidebar').click(filter)

      $('.triggerSidebar1').click(function() {
      $('body').css('overflow', 'hidden')
      })
      var sortby = function() {
      $('.sidebar1').css('display', 'none')

      }
      $('.hideSidebar1').click(sortby)
      // $('.overlay').click()
      });
      </script>
@endsection

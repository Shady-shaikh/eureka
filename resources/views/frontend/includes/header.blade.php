@php
use App\Models\frontend\Categories;
use App\Models\backend\HeaderNotes;
use App\Models\frontend\Cart;
use App\Models\backend\FrontendImages;

$categories = Categories::where('visibility',1)->get();
$header_notes = HeaderNotes::where('visibility',1)->get();
$logo = FrontendImages::where('image_code','logo')->first();
//echo "<pre>";print_r($categories);exit;
@endphp


<a id="return-to-top" href="javascript:" ><i class="fa fa-chevron-up"></i></a>

<div class="dim"></div>
<section  class="fixed-top">
		<section class="top-header">
			<div class="container-fluidcustom">
				<div class="row divider">
					<div class="col-lg-1 dn top-column text-center">
						<a class="m-left " href="{{ route('downloadapp') }}"><i class="fa fa-mobile mr-2" aria-hidden="true"></i>DOWNLOAD APP</a>
					</div>

					<div class="col-lg-1 dn top-column text-center">
						<a href="{{ url('faqs') }}"><i class="fa fa-headphones mr-2" aria-hidden="true"></i>CUSTOMER CARE</a>
					</div>
					<div class="col-lg-6 dn top-column text-center tickercontainer">
											<header class="ag-header">
					<div class="ag-ticker-block">
					<div class="ag-format-container">
					<div class="ag-ticker_box-wrap">
					<div class="ag-ticker_box">
					 <div class="js-marquee">
						 <ul class="ag-ticker_list">
							 @if(isset($header_notes))
								@foreach($header_notes as $header_note)
							 <li class="ag-ticker_item">
								 <a href="#" class="ag-ticker_link">
									 <div class="ag-ticker_news-item">
										 {{ $header_note->header_note_text }}
									 </div>
								 </a>
							 </li>
							 @endforeach
							@endif
						 </ul>
					 </div>
					</div>
					</div>
					</div>
					</div>
					</header>

										 </div>


					@if( auth()->check() )
						<div class="col-lg-1 col-3 top-column text-center">
							<div class="dropdown-popup">

								<nav class="logout-dropdown">
								<ul>
								<li>
								 <a href="#"><i class="fa fa-user mr-2" aria-hidden="true"></i><span class="dn">Hi, {{ strtok(auth()->user()->name,' ') }}</span></a>
								 <ul class="dropdown-inner">
									 <li>
										 <i class="fa fa-caret-up" aria-hidden="true"></i>
										 <a href="{{ url('/logout') }}">Logout</a>
									 </li>
									 </ul>
								 </li>

						 </ul>
					 </nav>

							</div>


						</div>
						<div class="col-lg-1 col-3  top-column text-center">
							<a href="{{ url('/myaccount/profile') }}"><i class="fa fa-user-circle mr-2" aria-hidden="true"></i><span class="dn">YOUR ACCOUNT</span></a>
						</div>
					@else
						<div class="col-lg-1 col-3 top-column text-center">
							<a href="{{ url('/login') }}"><i class="fa fa-unlock-alt mr-2" aria-hidden="true"></i><span class="dn">LOGIN</span></a>
						</div>
						<div class="col-lg-1 col-3  top-column text-center">
							<a href="{{ url('/myaccount/profile') }}"><i class="fa fa-user-circle mr-2" aria-hidden="true"></i><span class="dn">YOUR ACCOUNT</span></a>
						</div>
					@endif

					<div class="col-lg-1 col-3  top-column text-center">
						<a  href="{{ url('/wishlists') }}"><span class="dn">YOUR</span><span class="heart"><i class="fa fa-heart mr-1 ml-1" aria-hidden="true"></i></span><span class="dn">WISHLIST</span></a>
					</div>

					<div class="col-lg-1 col-3 top-column text-center">
						<a class="cart-link" href="{{ url('/cart') }}">

								@if( auth()->check() )
									@php
										$cart = Cart::where('user_id',auth()->user()->id)->sum('qty');
									@endphp
									@if($cart>0)
											<span class="icon-count">
										{{ $cart }}
										</span>
									@else
										<i class="fa fa-cart-plus" aria-hidden="true"></i>
									@endif
								@else
                                {{--  user not logged in  --}}
                                {{--  //session cart  --}}
                                @if (session()->has('cart'))
                                @php
                                $qty_sum = 0;
                                    $cart_count  = count(session('cart'));
                                   foreach(session('cart') as $name => $value){
                                       $qty_sum = $qty_sum+$value['quantity'];
                                   }

                                @endphp
                                <span class="icon-count">
                                    {{ $qty_sum }}
                                    </span>
                                @endif
                                {{--  //session cart  --}}
									<i class="fa fa-cart-plus" aria-hidden="true"></i>
								@endif
								<span class="dn">CART </span>
						</a>
					</div>
				</div>
			</div>
		</section>
		<!-- <nav>
		<ul>


	</ul>
</nav> -->



<!--Topbar End-->

<!--Navigationbar start-->
<nav id="navigation1" class="navigation navbar navbar-expand-lg " >
	<div class="row navigationbar mx-0">
		<div class="col-lg-2 col-md-6 col-sm-6 col-12 left-pad-adj15">
			<a class="navbar-brand" href="{{ url('/') }}">
				@if(isset($logo->image_url))
					<img class="img-fluid" src="{{ asset('public/backend-assets/uploads/frontend_images/') }}/{{ $logo->image_url }}" alt="">
				@else
					<img class="img-fluid" src="{{ asset('public/frontend-assets/images/logoparwani.png') }}" alt="">
				@endif
			</a>
			<button class="navbar-toggler text-center" onclick="openNav()" data-toggle="collapse" data-target="#collapsibleNavbar-two">
				<span class="navbar-toggler-icon text-dark"><i class="fa fa-bars" aria-hidden="true"></i></span>
			</button>
		</div>

			<div class="nav-menus-wrapper collapse navbar-collapse col-lg-6 col-md-5 col-sm-4 col-12 " id="collapsibleNavbar-two">
				<button class="close-menu-btn" onclick="closeNav()" ><i class="fa fa-times" aria-hidden="true"></i></button>
				<ul class="nav-menu m-auto  link1">
					@if($categories)
					@foreach($categories as $category)
					@if($category->has_subcategories == '1')
					<li class="menulink link1">
						@if(isset($category->text_color))
							<a class="sweep-to-bottom"  href="{{ url('sf/') }}/{{ $category->category_slug }}">
								<span style="color:{{ $category->text_color }};">{{ $category->category_name }}</span>
							</a>
						@else
							<a class="sweep-to-bottom"  href="{{ url('sf/') }}/{{ $category->category_slug }}">{{ $category->category_name }}</a>
						@endif
						@if(isset($category->subcategories))
						<div class="megamenu-panel container-fluidcustom">
							<div class="megamenu-lists">
								@foreach($category->subcategories as $subcategory)
								 <ul class="megamenu-list list-col-2">
										<li class="megamenu-list-title {{ $category->drop_down_sub_category_class }}">
											<a href="{{ url('ss/') }}/{{ $category->category_slug }}/{{ $subcategory->sub_category_slug }}">{{ $subcategory->subcategory_name }}</a>
										</li>
										@if(isset($subcategory->subsubcategories))
										@foreach($subcategory->subsubcategories as $subsubcategory)
											<li >
												<a  href="{{ url('s/') }}/{{ $category->category_slug }}/{{ $subcategory->sub_category_slug }}/{{ $subsubcategory->sub_sub_category_slug }}" @if(isset($category->drop_down_menu_text_color)) onMouseOver="this.style.color='{{ $category->drop_down_menu_text_color }}'" onMouseOut="this.style.color='#343a40'" @endif>{{ $subsubcategory->sub_subcategory_name }}</a>
											</li>
										@endforeach
										<!-- <div class="view-color-div float-left"><a href="{{ url('s/') }}/{{ $category->category_slug }}/{{ $subcategory->sub_category_slug }}/" class="see-more mega-menu-view-color ">View More</a></div> -->
										@endif
								 </ul>
								 @endforeach
							</div>

						</div>
						@endif
					</li>
					@else

					@endif
				 @endforeach
				 @endif
				 <li class="menulink"><a class="sweep-to-bottom" href="{{ url('/deals') }}"><span class="todays-special">TODAY’S SPECIAL DEALS</span></a></li>
				 <li class="menulink"><a class="sweep-to-bottom" href="{{ url('/hotoffer') }}">Hot Offers</a></li>

					 <li class="menulink more-dropdown" >
						 <a class="sweep-to-bottom"  href="#">More</a>
						 <div class="megamenu-panel container-fluidcustom">
							 <div class="megamenu-lists ">
									<ul class="megamenu-list top-list">
										 <li><a href="{{ route('coupons') }}">Dadreeios Coupons</a></li>

									</ul>
								</div>
									</div>
								</li>
							</ul>
</div>



				<div class="col-lg-4 col-md-6 col-sm-6 col-12 right-pad-adj15 my-auto">

					<form class="input-group search-box" id="search-box" action="{{url('search')}}" method="get">
						<input class="search" type="text" placeholder="Search For Products, Categories &amp; More..." value="{{old('q')}}" aria-label="Search" id="search_input" required>
						<button id="search-button" type="submit">
               			 <span><i id="search-btn" class="fa fa-search"></i></span>
            			</button>
					</form>
				</div>
</div>
		</nav>
</section>
<!--Navigationbar end-->
<script>
$(document).ready(function () {

	$('.navbar .dropdown').hover(function () {
		$(this).find('.dropdown-menu').first().stop(true, true).slideDown(150);
	}, function () {
		$(this).find('.dropdown-menu').first().stop(true, true).slideUp(105)
	});
	$("#search_input").on('input',function(e)
	{
		console.log($(this).val());
		$("#search-box").attr("action","{{url('/')}}/search/" + $(this).val());
	});
});
</script>

<script>

$('.link1').hover(function(){
    $('.dim').fadeIn(200);
},function(){
    $('.dim').fadeOut(200);
});
</script>

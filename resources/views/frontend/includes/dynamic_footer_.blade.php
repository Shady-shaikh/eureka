@php
use App\Models\backend\Cmspages;
use App\Models\backend\Footer;
use App\Models\backend\SubSubCategories;
use App\Models\backend\Categories;

$quick_link_pages = Cmspages::where('column_type','quick_links')->where('cms_pages_footer',1)->where('show_hide',1)->get();
$conntect_us_pages = Cmspages::where('column_type','conntect_us')->where('cms_pages_footer',1)->where('show_hide',1)->get();
$our_policies_pages = Cmspages::where('column_type','our_policies')->where('cms_pages_footer',1)->where('show_hide',1)->get();

$footer = Footer::with('categories')->first();
$footer_categories = [];
if(isset($footer->categories) && isset($footer->categories))
{
	foreach($footer->categories as $cat_ids)
	{
		$footer_cat_ids[$cat_ids->category_id][] = $cat_ids->sub_subcategory_id;
	}

}
//dd($footer);
@endphp
<!--footer start-->
<footer>
	<div class="container-fluidcustom">
			<div class="row row-bg  pt-4  pb-4">
			    <div class="col-lg-12 col-md-12 col-sm-12 col-12 px-0">
    				@if(isset($footer) && isset($footer->footer_description))
    					{!! $footer->footer_description !!}
    				@else
    					<p>Dadreeios.com is India’s latest, stylish & an amazing online shopping platform which aim to provide best online experience to shoppers across India.Changing in fashion
    						is very necessary to keep life interesting.
    					</p>
    				@endif
				</div>
			</div>
		</div>


	<div class="footer-img">
		@if(isset($footer) && isset($footer->footer_image4))
			<img class="img-fluid w-100 " src="{{ asset('public/backend-assets/uploads/footer-assets/above_category_image/') }}/{{ $footer->footer_image1 }}" alt="footer image">
		@endif

	</div>
	<div class="container-fluidcustom pt-1">
			<div class="row row-bg row-border pt-4">
			<div class="col-lg-12 col-md-12 col-sm-12 col-12 px-0">
				<div class="footer-section">
					<h1 class="men-women-head">
						@if(isset($footer) && isset($footer->footer_category_description))
							{!! $footer->footer_category_description !!}
						@else
							DADREEIOS.COM'S TOP CATEGORIES FOR MEN AND WOMEN
						@endif
					</h1>
				</div>

				<div class="men-women-padd">
					@if(isset($footer->categories) && isset($footer->categories))
						@if(isset($footer_cat_ids))
							@foreach($footer_cat_ids as $category_id => $subcategory_ids)
							@php
								$footer_cat = Categories::where('category_id',$category_id)->first();
							@endphp
						<ul class="navigation-footer nav men-women-w">

							<span class="footer-category-head ">{{ $footer_cat->category_name }} :</span>
								@if(isset($subcategory_ids))
									@php
										$footer_sub_cats = SubSubCategories::whereIn('sub_subcategory_id',$subcategory_ids)->with('category','subcategory')->get();
										//dd($footer_sub_cats);
									@endphp
									@foreach($footer_sub_cats as $footer_sub_cat)
									<li class="nav-item pt-2">
										<a class=" nav-link active  " href="{{ url('s/') }}/{{ isset($footer_sub_cat->category)?$footer_sub_cat->category->category_slug:'' }}/{{ isset($footer_sub_cat->subcategory)?$footer_sub_cat->subcategory->sub_category_slug:'' }}/{{ $footer_sub_cat->sub_sub_category_slug }}">{{ $footer_sub_cat->sub_subcategory_name }}</a>
									</li>
									
									@endforeach
									@endif
							</ul>
							@endforeach

							@endif
						@endif
				</div>

				
			</div>
			</div>
			<div class="row row-bg no-gutters heading-pt-footer">
				<div class="col-lg-3 pt-2  px-0 col-md-12 col-sm-12 col-12">
					@if(isset($footer) && isset($footer->footer_image1))
						<a href="#"><img class="img-fluid w-100 " src="{{ asset('public/backend-assets/uploads/footer-assets/left_image1/') }}/{{ $footer->footer_image2 }}" alt=""></a>
					@endif
				</div>

				<div class="col-lg-2 pt-4 col-md-4 col-sm-4 col-12">
					<div class="footer-header-one">
						<h4>QUICK LINKS</h4>
						@if(isset($quick_link_pages) && count($quick_link_pages)>0)
						<ul class="info-foot pt-3">
							@foreach($quick_link_pages as $quick_link_page)
							<li><a href="{{ url('pages/'.$quick_link_page->cms_slug) }}">{{ $quick_link_page->cms_pages_title }}</a></li>
							@endforeach
							<li><a href="{{ url('faqs') }}">FAQs</a></li>
						</ul>
						@endif
					</div>
				</div>

				<div class="col-lg-2 pt-4 col-md-4 col-sm-4 col-12">
					<div class="footer-header-two">
						<h4>CONNECT WITH US ON</h4>
						@if(isset($conntect_us_pages) && count($conntect_us_pages)>0)
						<ul class="info-foot pt-3">
							@foreach($conntect_us_pages as $conntect_us_page)
							<li><a href="{{ $conntect_us_page->cms_pages_link }}" target="_blank">{{ $conntect_us_page->cms_pages_title }}</a></li>
							@endforeach
						</ul>
						@endif


					</div>
				</div>
				<div class="col-lg-2 pt-4 col-md-4 col-sm-4 col-12">
					<div class="footer-header-three">
						<h4>OUR POLICIES</h4>

						@if(isset($our_policies_pages) && count($our_policies_pages)>0)
						<ul class="info-foot pt-3">
							@foreach($our_policies_pages as $our_policies_page)
							<li><a href="{{ url('pages/'.$our_policies_page->cms_slug) }}">{{ $our_policies_page->cms_pages_title }}</a></li>
							@endforeach
						</ul>
						@endif
					</div>
				</div>
				<div class="col-lg-3 pt-2  px-0 col-md-12 col-sm-12 col-12">
					@if(isset($footer) && isset($footer->footer_image2))
						<a href="#"><img class="img-fluid w-100" src="{{ asset('public/backend-assets/uploads/footer-assets/left_image2/') }}/{{ $footer->footer_image3 }}" alt=""></a>
					@endif
					<div class="footer-sub mt-5">
						<p>Subscribe to Our NewsLetter & Get Updates of Our Latest Products, Sale Offers & Exclusive Discounts.</p>
					</div>
					
					<form class="footer-search" action="{{route('newsletter')}}" method="post">
						@csrf
						<input class="search-in" type="email" name="email"  placeholder="Enter Email ID" required>
							<input class="btn suggest-btn" type="submit" value=" SUBSCRIBE " >
					</form>
				</div>
			</div>

			<div class="row row-bg pt-4 ">
					<div class="col-lg-3 px-0 col-md-12 col-sm-12 col-12 pb-4">
						@if(isset($footer) && isset($footer->footer_image3))
							<a href="#"><img class="img-fluid w-100" src="{{ asset('public/backend-assets/uploads/footer-assets/right_image/') }}/{{ $footer->footer_image4 }}" alt=""></a>
						@endif
					</div>
					<div class="col-lg-7 col-md-12 col-sm-12 col-12 align-self-end justify-content-center  pb-4">
						<section class="mar">
							<div class="footer-header">
								<h4>Most Trusted Payment Methods</h4>
							</div>
									<a  href="#"><img height="40" class="pay-img img-fluid" src="{{ asset('public/frontend-assets/images/payment/card_visa.png') }}"/></a>
									<a  href="#"><img height="40" class="pay-img img-fluid" src="{{ asset('public/frontend-assets/images/payment/card_master.png') }}"/></a>
									<a  href="#"><img height="40" class="pay-img img-fluid" src="{{ asset('public/frontend-assets/images/payment/Amex.png') }}"/></a>
									<!--<a  href="#"><img height="40" class="pay-img img-fluid" src="{{ asset('public/frontend-assets/images/payment/card_paypal.png') }}"/></a>-->
									<a  href="#"><img height="40" class="pay-img img-fluid" src="{{ asset('public/frontend-assets/images/payment/paytm.png') }}"/></a>
									<a  href="#"><img height="40" class="pay-img img-fluid" src="{{ asset('public/frontend-assets/images/payment/google-pay.png') }}"/></a>
									<a  href="#"><img height="40" class="pay-img img-fluid" src="{{ asset('public/frontend-assets/images/payment/bhim.png') }}"/></a>
									<a  href="#"><img height="40" class="pay-img img-fluid" src="{{ asset('public/frontend-assets/images/payment/Rupay.png') }}"/></a>
									<a  href="#"><img height="40" class="pay-img img-fluid" src="{{ asset('public/frontend-assets/images/payment/Cashon.png') }}"/></a>
									<a  href="#"><img height="40" class="pay-img img-fluid" src="{{ asset('public/frontend-assets/images/payment/netbanking.png') }}"/></a>
						</section>
					</div>
					<div class="col-lg-2 col-md-12 col-sm-12 col-12 align-self-end text-center pb-4 p-0">
						<div class="footer-dmca">
							<a href="#">DMCA</a>
						</div>
					</div>
			</div>
		</div>

		<p class="footer-copyright text-center mb-0">© {{date("Y")}} <!-- <a class="site-link" href="#"> --> www.dadreeios.com.<!-- </a> --> All Rights Reserved.</p>

</footer>


<!--footer end-->


<!-- Default Statcounter code for Dadreeios -->
<script type="text/javascript">
var sc_project=12779320;
var sc_invisible=1;
var sc_security="d83c685d";
</script>
<script type="text/javascript"
src="https://www.statcounter.com/counter/counter.js"
async></script>
<noscript><div class="statcounter"><a title="Web Analytics"
href="https://statcounter.com/" target="_blank"><img
class="statcounter"
src="https://c.statcounter.com/12779320/0/d83c685d/1/"
alt="Web Analytics"
referrerPolicy="no-referrer-when-downgrade"></a></div></noscript>
<!-- End of Statcounter Code -->

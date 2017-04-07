@extends('granitecms_dev.theme.views.layouts.default')

@section('content')

		<!-- ********** BANNER ********** -->
		<div class="banner">
			<div class="row fullwidth">
				<div class="col span12 bannerSlider">
					<div id="bannerSlider">
						@foreach($banners as $banner)
							<div>
								<a href="{{ $banner->link }}">
									@if( $banner->headline )
										<div class="text">
											<h2>{{ $banner->headline }}</h2>
											<p>{{ $banner->text }}</p>
											<p class="more">Learn More <i class="fa fa-angle-right"></i></p>
										</div>
									@endif
									<div class="image">
										<img src="{{ $banner->url }}"  />
									</div>
								</a>
							</div>

						@endforeach
						
					</div>
				</div><!--/col-->
			</div><!--/row-->
		</div><!--/banner-->

		<div class="container contentWrap">
			<div class="row sample">
				<div class="col span6">
					<div class="inner">
						span6 ( 6 + 6 = 12 )
					</div>
				</div>
				<div class="col span6">
					<div class="inner">
						span6 ( 6 + 6 = 12 )
					</div>
				</div>
			</div>

			<div class="row sample">
				<div class="col span4">
					<div class="inner">
						span4 (4 + 4 + 4 = 12)
					</div>
				</div>
				<div class="col span4">
					<div class="inner">
						span4 (4 + 4 + 4 = 12)
					</div>
				</div>
				<div class="col span4">
					<div class="inner">
						span4 (4 + 4 + 4 = 12)
					</div>
				</div>
			</div>

			<div class="row sample">
				<div class="col span3">
					<div class="inner">
						span3 (3 + 3 + 3 + 3 = 12)
					</div>
				</div>
				<div class="col span3">
					<div class="inner">
						span3 (3 + 3 + 3 + 3 = 12)
					</div>
				</div>
				<div class="col span3">
					<div class="inner">
						span3 (3 + 3 + 3 + 3 = 12)
					</div>
				</div>
				<div class="col span3">
					<div class="inner">
						span3 (3 + 3 + 3 + 3 = 12)
					</div>
				</div>
			</div>

			<div class="row sample">
				<div class="col span2">
					<div class="inner">
						span2 (2 + 7 + 3 = 12)
					</div>
				</div>
				<div class="col span7">
					<div class="inner">
						span7 (2 + 7 + 3 = 12)
					</div>
				</div>
				<div class="col span3">
					<div class="inner">
						span3 (2 + 7 + 3 = 12)
					</div>
				</div>
			</div>

			<div class="row sideItems">
				<ul class="sideItemsList">			
					<li class="col span3 " id="21" >
						<a class="inner" href="/">
							<h3>Side Item Content Here</h3>
							<p>Can be heading, paragraph, image or font icon.</p>
							<p class="btn">More</p>
						</a>
						
					</li>
					<li class="col span3 " id="22" >
						<a class="inner" href="/">
							<h3>Side Item Content Here</h3>
							<p>Can be heading, paragraph, image or font icon.</p>
							<p class="btn">More</p>
						</a>
					</li>

					<li class="col span3 " id="23" >
						<a class="inner" href="/">
							<h3>Side Item Content Here</h3>
							<p>Can be heading, paragraph, image or font icon.</p>
							<p class="btn">More</p>
						</a>
					</li>
				

					<li class="col span3 " id="24" >
						<a class="inner" href="/">
							<h3>Side Item Content Here</h3>
							<p>Can be heading, paragraph, image or font icon.</p>
							<p class="btn">More</p>
						</a>
					</li>
				</ul>
			</div><!--/sideItems-->
		</div><!--/container-->
@stop


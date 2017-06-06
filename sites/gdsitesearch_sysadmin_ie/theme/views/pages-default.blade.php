@extends('gdsitesearch_sysadmin_ie.theme.views.layouts.default')

@section('content')
<div class="banner innerPage">
	<div class="row fullwidth">
		<div class="col span12 bannerSlider">
			
			<div id="bannerSlider">
				
				<div>
					<div class="image">
						<img src="{{ $page->page_banner }}" draggable="false">
					</div>
				</div>
				
			</div>
		</div><!--/col-->
	</div><!--/row-->
</div>


<div class="container contentWrap">
   <div class="row">
      <div class="col span3 sidebar">
         <div class="inner">
            <ul class="sideItemsList">
               <li class="col span3 " id="22" >
                  <a class="inner" href="#!">
                     <h3>Side Item Content Here</h3>
                     <p>Can be heading, paragraph, image or font icon.</p>
                     <p class="btn">More</p>
                  </a>
               </li>
               <li class="col span3 " id="23" >
                  <a class="inner" href="#!">
                     <h3>Side Item Content Here</h3>
                     <p>Can be heading, paragraph, image or font icon.</p>
                     <p class="btn">More</p>
                  </a>
               </li>
            </ul>
         </div>
         <!--/inner-->
      </div>
      <!--/sidebarLeft-->
      <div class="col span9 content">
         <div class="inner">
            <div id="path">
               <ul>
                  <li class='first'><a href="/" title="Home">Home</a></li>
                  <li class="sel"><a href="{{ page($page->page_slug) }}" title="{{ $page->page_title }}">{{ $page->page_title }}</a></li>
               </ul>
            </div>
            <h1 id="pageTitle">{{ $page->page_title }}</h1>
            <div class="copy">
               <p>April 03 2017</p>
               {!! $page->page_content !!}
            </div>
         </div>
         <!--/content-->
      </div>
      <!--/inner-->
   </div>
   <!--/row-->
</div>
<!--/container-->

@stop
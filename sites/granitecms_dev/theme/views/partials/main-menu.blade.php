<?php
	$main_menu = getAllMenuPages(getMenuByName('Main Menu'))->get(0);
	$pages = $main_menu['children'];
?>

<a class="toggleMainNav" href="javascript:" title="show navigation"><span>Home</span><i class="fa fa-bars"></i></a>
<ul class="mainNavList">
	<li class="lev1"><a href="/">Home</a></li>
	@foreach($pages as $page)
		<li class="lev1"><a href="{{ page($page['slug']) }}" title="{{ $page['text'] }}">{{ $page['text'] }}</a></li>
	@endforeach
</ul>


@if( count( $children = \App\MenuItem::childrenOf($item->id) ) > 0 )
	@if (Auth::user()->can("view_{$item->page}"))
		<li>
			<a><i class="fa fa-edit"></i> {{ $item->name }} <span class="fa fa-chevron-down"></span></a>
			<ul class="nav child_menu">
				@each('partials.menu-item', $children, 'item')
			</ul>
		</li>
	@endif
@else
	@if (Auth::user()->can("view_{$item->page}"))
		<li>
			<a href="{{ $item->link }}"><i class="fa fa-edit"></i> {{ $item->name }}</span></a>
		</li>
	@endif
@endif
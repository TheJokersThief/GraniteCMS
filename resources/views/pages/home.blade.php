@extends('layouts.app')

@section('content')
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
		<cfset qryLHSItems = APPLICATION.Site.GetPageLHSItems(REQUEST.pageInfo.page_LHSItemList)>
		<cfoutput>#APPLICATION.Site.RenderLHSItems(qryLHSItems)#</cfoutput>
	</div><!--/sideItems-->

@stop
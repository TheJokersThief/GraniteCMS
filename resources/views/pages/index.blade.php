@extends('layouts.app')

@section('content')
<div class="right_col" role="main">
   <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
         <div class="x_title">
            <h2>{{ isset($meta_info['title']) ? ucwords($meta_info['title']) : ucfirst($page) }}</h2>
            <div class="clearfix"></div>
         </div>
         <div class="x_content">
            <div class="table-responsive">
               <table class="table table-striped jambo_table bulk_action">
                  <thead>
                     <tr class="headings">
                        <th>
                           <input type="checkbox" id="check-all" class="flat">
                        </th>
                        @foreach( $shortlist as $heading )
                           <th class="column-title">{{ ucwords( str_replace('_', ' ', $heading) ) }}</th>
                        @endforeach
                        <th class="column-title no-link last"><span class="nobr">Action</span>
                        </th>
                        <th class="bulk-actions" colspan="7">
                           <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                        </th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach( $items as $item )
                        <tr class="even pointer">
                           <td class="a-center ">
                              <input type="checkbox" class="flat" name="table_records">
                           </td>
                           @foreach( $shortlist as $key )
                              <td class="">{{ $item->$key }}</td>
                           @endforeach
                           <td class=" last">
                              <a href="{{ route('template-show', ['page' => $page, 'id' => $item->id ] ) }}" class="label label-info">View</a>
                              <a href="{{ route('template-edit', ['page' => $page, 'id' => $item->id ] ) }}" class="label label-info">Edit</a>
                              <a href="{{ route('template-delete', ['encrypted_id' => encrypt( $item->{$meta_info['key']} ), 'page' => $page]) }}" class="label label-danger">Delete</a>
                           </td>
                        </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- /page content -->

@stop
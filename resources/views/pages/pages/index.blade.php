@extends('layouts.app')

@section('extra-css')
   <link rel="stylesheet" type="text/css" href="{{ asset('js/components/inspire-tree-1.10.6/dist/inspire-tree.css') }}">
@stop

@section('content')
<div class="right_col" role="main">
   <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="x_panel">
         <div class="x_title">
            <h2>{{ isset($meta_info['title']) ? ucwords($meta_info['title']) : ucfirst($page) }}</h2>
            <div class="clearfix"></div>
         </div>
         <div class="x_content">
            <div class="tree left"></div>
         </div>
      </div>
   </div>
</div>
<!-- /page content -->

@stop

@section('extra-js')
   <script src="{{ asset('js/components/inspire-tree-1.10.6/dist/inspire-tree-bundled.min.js') }}"></script>


    <?php
        $menus = \App\MenuItem::where('parent', 0)->where('name', '<>', 'CMS Menu')->get();

        $pages = getAllMenuPages($menus, true, ["id"]);
    ?>

   <script>
        var editRouteScheme     = "{!! route('template-edit', ['page' => 'pages', 'id' => 'replace_me']) !!}",
            deleteRouteScheme   = "{!! route('template-delete', ['page' => 'pages', 'encrypted_id' => 'replace_me']) !!}",
            addMenuRouteScheme  = "{!! route('template-create', ['page' => 'menus']) !!}",
            addRouteScheme      = "{!! route('template-create', ['page' => 'pages']) !!}",
            addWithMenuRouteScheme      = "{!! route('template-create', ['page' => 'pages', 'menu_id' => 'replace_menu_id']) !!}",
            addWithParentRouteScheme      = "{!! route('template-create', ['page' => 'pages', 'menu_id' => 'replace_menu_id', 'parent_id' => 'replace_parent_id']) !!}";

        var tree = new InspireTree({
            target: '.tree',
            editing: {
                add: true,
                remove: true
            },
            editable: true,
            data:
            {!! $pages->toJson() !!}

        });


        tree.on('node.click', function(event, node) {
            event.preventTreeDefault(); // Cancels default listener

            if( node.menu == null ){
                window.location = editRouteScheme.replace('replace_me', node.id);
            }
        });

        tree.on('node.added', function(node) {
            console.log(node);

            if( node.itree.parent == null ){
                return window.location = addMenuRouteScheme;
                console.log("no parent");
            }

            if( node.itree.parent.menu === true ){
                return window.location = addWithMenuRouteScheme.replace('replace_menu_id', node.itree.parent.menu_id);
                console.log("child of top parent");
            }

            if( node.itree.parent.menu == null ){
                return window.location = addWithParentRouteScheme.replace('replace_menu_id', node.itree.parent.menu_id).replace('replace_parent_id', node.itree.parent.id);
                console.log("normal parent");
            }
        });

        
        tree.on('node.removed', function(node) {
            console.log(node);

            if( node.menu == null ){
                window.location = deleteRouteScheme.replace('replace_me', node.encrypted_id);
            }
        });

        

        


   </script>
@stop
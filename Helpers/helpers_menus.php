<?php

/**
 * Get all pages from a selected menu(s)
 * @param  array   $menus
 * @param  boolean $add_encrypted_field Enables the encrypted_fields variable
 * @param  array   $encrypted_fields
 * @return array
 */
function getAllMenuPages($menus, $add_encrypted_field = false, $encrypted_fields = ['id'])
{

    if (is_a($menus, '\App\MenuItem')) {
        // If single menu item, convert to collection
        $collection = new Illuminate\Database\Eloquent\Collection();
        $collection->add($menus);
        $menus = $collection;
    }

    if ((is_a($menus, 'Illuminate\Database\Eloquent\Collection') || is_a($menus, 'Illuminate\Support\Collection'))
        && $menus->isNotEmpty()) {

        $pages = collect([]); // empty collection
        $counter = 0;
        foreach ($menus as $menu) {
            $page = [];
            $page["text"] = $menu->name;
            $page["menu"] = true;
            $page["menu_id"] = $menu->id;

            $children = collect(\App\Page::where('menu_id', $menu->id)->where('parent_id', 0)->select('id', 'menu_id', 'parent_id', 'page_title as text', 'page_slug as slug')->get());
            $page['children'] = $children->map(function ($item, $key) use ($menu) {
                return getAllPageChildren($item, $menu);
            });

            $pages->push($page);
            $counter++;
        }

        if ($add_encrypted_field) {
            $pages = recursiveEncryptCollection($pages, "children", $encrypted_fields);
        }

        return $pages;
    } else {
        throw new \Exception(errorMsg('menus_incorrect_menu_argument'));
    }
}

function getMenuByName($name)
{
    $menu = \App\MenuItem::where('parent', 0)->where('name', $name)->where('site', getCurrentSideID())->first();
    if ($menu != null) {
        return $menu;
    } else {
        throw new \Exception(errorMsg('menus_no_menu'));
    }
}

function getMenuByID($id)
{
    $menu = \App\MenuItem::where('parent', 0)->where('id', $id)->where('site', getCurrentSideID())->first();
    if ($menu != null) {
        return $menu;
    } else {
        throw new \Exception(errorMsg('menus_no_menu'));
    }
}

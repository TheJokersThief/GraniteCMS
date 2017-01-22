<?php

function getAllPageChildren($root, $menu)
{
    $root->children = \App\Page::where('menu_id', $menu->id)->where('parent_id', $root->id)->select('id', 'page_title as text')->get();

    if ($root->children->isEmpty()) {
        return $root;
    } else {
        foreach ($root->children as $child) {
            $root->children[$child->id] = getAllPageChildren($child, $menu);
        }

        $root->children = $root->children->unique();
        return $root;
    }
}

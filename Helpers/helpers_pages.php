<?php
/**
 * Get all of the children of a root menu item
 * @param  integer $root Root menu item
 * @param  integer $menu Menu ID
 * @return array
 */
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

/**
 * Return URL to a page
 * @param  string $page
 * @return string
 */
function page($page)
{
    return route('page', ['page' => $page]);
}

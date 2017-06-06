<?php

namespace Sites\gdsitesearch_sysadmin_ie\theme\controllers;

use App\Http\Controllers\Controller;
use Sites\gdsitesearch_sysadmin_ie\theme\Query;
use Sites\gdsitesearch_sysadmin_ie\theme\Site;
use Sites\gdsitesearch_sysadmin_ie\theme\Tag;
use Wamania\Snowball\English;

class TagController extends Controller
{

    public function __construct()
    {
        $this->stemmer = new English();

        $hook = config('hooks');
        $hook->addHook('after_CRUD_POST_processing', 10, [$this, 'processTagInput']);
        $hook->addHook('during_CRUD_field_display', 10, [$this, 'renderTagInput']);
    }

    /**
     * Return all tags
     * @return JSON
     */
    public function index()
    {
        $tags = Tag::all();
        return response()->json($tags);
    }

    /**
     * Return info on one tag
     * @param  string $tag
     * @return JSON
     */
    public function show($tag)
    {
        $tag = $this->stemmer->stem($tag);

        $tag = Tag::where('tag', $tag)->first();
        if ($tag != null) {
            return apiResponse(SUCCESS, $tag);
        }

        return apiResponse(NO_CONTENT);
    }

    /**
     * After CRUD form submission, process request input
     * @param  Request  $request    Request submitted by the form
     * @param  array    $fields     Array of fields in the form
     * @param  array    $set_values Values to be set in the DB
     * @param  int      $id         ID of the item if in edit mode
     */
    public function processTagInput($request, $fields, $set_values, $id)
    {
        if ($request->page == 'websites') {
            foreach ($fields as $field) {
                if ($field['type'] == 'custom_taginput') {
                    $tags = $request[$field['name']];
                    $diff_tags = $this->diffTags($tags, $id);
                    $this->storeFullTags($tags, $id);
                    $this->batchStore($diff_tags, $id);
                }
            }
        }
    }

    /**
     * Store the full string of tags for each site (i.e. not the stemmed version)
     * @param  string   $tags
     * @param  int      $id
     */
    public function storeFullTags($tags, $id)
    {
        $tag = Site::find($id);
        $tag->tags_full = $tags;
        $tag->save();
    }

    /**
     * Remove deleted tags and list new tags
     * @param  string   $tags   Comma-separated string of tags
     * @param  int      $id
     * @return string           Comma-separated string of new tags
     */
    public function diffTags($tags, $id)
    {
        $tag = Site::find($id);
        $new_tags = explode(',', $tags);
        $old_tags = explode(',', $tag->tags_full);
        $diff = array_diff($new_tags, $old_tags);

        $return_array = [];
        foreach ($diff as $item) {
            // For every different item

            if (in_array($item, $new_tags)) {
                // If it's in new tags, it's a new tag
                $return_array[] = $item;
            }

            if (in_array($item, $old_tags)) {
                // If it's in old tags, it has been removed and we
                // need to remove the site ID from the set
                $this->removeTag($item, $id);
            }
        }

        return implode(',', $return_array);
    }

    /**
     * Before CRUD form rendering, render our custom inputs
     * @param  array $field Info about the input field
     * @param  mixed $value The value retrieved from the DB (if applicable)
     * @return View
     */
    public function renderTagInput($field, $value)
    {
        if (request()->page == 'websites') {
            $siteID = request()->route('id');

            // -----------------------------------------------------------
            //  Commented code left in to demonstrate site search method
            //  within the postings lists
            // -----------------------------------------------------------
            // $tags = Tag::where('postings', 'LIKE', '%' . $siteID . '%')
            //     ->orWhere('postings', 'LIKE', '%' . $siteID . ',%')
            //     ->orWhere('postings', 'LIKE', '%,' . $siteID . ',%')
            //     ->orWhere('postings', 'LIKE', '%,' . $siteID . '%')
            //     ->pluck('tag');
            // $value = implode(',', $tags->toArray());
            // -----------------------------------------------------------

            $site = Site::find($siteID);
            $value = ($site != null) ? $site->tags_full : "";

            if ($field['type'] == 'custom_taginput') {
                return view('components.text')->with(['field' => $field, 'value' => $value]);
            }
        }
    }

    /**
     * Remove a site's ID from the tag postings set
     * @param  string   $tag
     * @param  int      $siteID
     */
    public function removeTag($tag, $siteID)
    {
        $tag = trim($tag);
        // Split on space to handle multi-word tag submissions
        $tags = explode(' ', $tag);

        foreach ($tags as $tag) {
            // Stem tag (i.e. get the word's base form)
            $tag = $this->stemmer->stem($tag);

            $tagObj = Tag::where('tag', $tag)->first();
            if ($tagObj != null) {
                // Tag exists, update instead of create
                $postings = json_decode($tagObj->postings);

                if (in_array($siteID, $postings)) {
                    if(($key = array_search($siteID, $postings)) !== false) {
                        unset($postings[$key]);
                    }

                    $tagObj->postings = json_encode($postings);
                    $tagObj->save();
                }
            } else {
                $tagObj = Tag::create(['tag' => $tag, 'postings' => json_encode([$siteID])]);
            }
        }
    }

    /**
     * Create/update the tag with the corresponding site ID
     * @param  string   $tag
     * @param  int      $siteID
     */
    public function store($tag, $siteID)
    {
        $tag = trim($tag);
        // Split on space to handle multi-word tag submissions
        $tags = explode(' ', $tag);

        foreach ($tags as $tag) {
            // Stem tag (i.e. get the word's base form)
            $tag = $this->stemmer->stem($tag);

            $tagObj = Tag::where('tag', $tag)->first();
            if ($tagObj != null) {
                // Tag exists, update instead of create
                $postings = json_decode($tagObj->postings);

                if (!in_array($siteID, $postings)) {
                    // Postings are a set, each ID must be unique
                    $postings[] = $siteID;
                    $tagObj->postings = json_encode($postings);
                    $tagObj->save();
                }
            } else {
                $tagObj = Tag::create(['tag' => $tag, 'postings' => json_encode([$siteID])]);
            }
        }
    }

    /**
     * Store a comma-separated list of tags
     * @param  string   $tags
     * @param  int      $siteID
     */
    public function batchStore($tags, $siteID)
    {
        if (!is_array($tags)) {
            // if it's a comma-separated list, convert to array
            $tags = explode(',', $tags);
        }

        foreach ($tags as $tag) {
            $this->store($tag, $siteID);
        }
    }

    /**
     * Get site IDs from tags
     * @param  string   $tags Comma-separated list of tags
     * @return JSON
     */
    public function get($tags)
    {
        if (!is_array($tags)) {
            $tags = explode(',', $tags);
        }

        $stemmed_tags = [];
        foreach ($tags as $tag) {
            $stemmed_tags[] = $this->stemmer->stem($tag);
        }

        $tags = Tag::getFromTags($stemmed_tags)->get();

        return apiResponse(SUCCESS, $tags);
    }

    /**
     * Search for sites via tags (similar to get())
     * @param  string   $tags Comma-separated list of tags
     * @return JSON
     */
    public function search($tags)
    {

        if (!is_array($tags)) {
            $tags = explode(',', $tags);
        }

        $stemmed_tags = [];
        foreach ($tags as $tag) {
            $multi_words = explode(' ', $tag);

            foreach ($multi_words as $word) {
                $stemmed_tags[] = $this->stemmer->stem($word);
            }
        }

        $search_tags = Tag::getFromTags($stemmed_tags)->get();

        $results = $this->mergePostings($search_tags);

        // Store log of query performed
        Query::create(['query' => implode(',', $tags), 'results' => json_encode($results)]);

        return apiResponse(SUCCESS, $results);
    }

    /**
     * Merge all site IDs and order by frequency of site
     * @param  string $tags
     * @return array[int]
     */
    public function mergePostings($tags)
    {
        $postings_lists = [];

        foreach ($tags as $tag) {
            $postings_lists = array_merge($postings_lists, json_decode($tag->postings));
        }

        $frequencies = array_count_values($postings_lists);
        asort($frequencies);

        return $frequencies;
    }
}

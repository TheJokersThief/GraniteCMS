<?php

namespace App\Core;

use App\Capability;
use App\Http\Controllers\SiteController;

class Capabilities
{

    const AVAILABLE_CAPABILITIES = ['view', 'create', 'delete', 'edit'];

    private $hooks;

    public function __construct()
    {
        $hook = config('hooks');
        $hook->addHook('after_CRUD_POST_processing', 10, [$this, "processCapability"]);
        $hook->addHook('before_CRUD_index_page_render', 10, [$this, "getCapabilityData"]);
        $hook->addHook('before_CRUD_edit_page_render', 10, [$this, "getIndivCapabilityData"]);
    }

    public function getCapabilityData($page, $items, $config)
    {
        $capabilities = Capability::all();

        $pages = [];
        foreach ($capabilities as $cap) {
            $regex = self::capabilityNameRegex();
            $matches = null;
            preg_match_all($regex, $cap->capability_name, $matches);

            if (isset($matches[2][0])) {
                $page_name = $matches[2][0];
                $pages[$page_name][$matches[1][0]] = $cap;
            }
        }

        return $pages;
    }

    public function getIndivCapabilityData($page, $items, $config, $id, $form)
    {

        if ($page == 'capabilities') {
            $initial_cap = Capability::where('id', $id)->first();

            if ($initial_cap != null) {
                $regex = self::capabilityNameRegex();
                $matches = null;
                preg_match_all($regex, $initial_cap->capability_name, $matches);

                if (isset($matches[2][0])) {
                    $page_name = $matches[2][0];

                    $caps = Capability::where('capability_name', 'LIKE', '%_' . $page_name)->get();

                    $data = new \stdClass();
                    $data->page_name = $page_name;

                    foreach ($caps as $cap) {
                        $regex = self::capabilityNameRegex();
                        $matches = null;
                        preg_match_all($regex, $cap->capability_name, $matches);

                        if (isset($matches[2][0])) {
                            $data->{$matches[1][0] . "_min_role"} = $cap->capability_min_level;
                        }
                    }

                    $form->addValues($data);
                }
            }
            return [];
        }
    }

    public function processCapability($request, $fields, $set_values, $id)
    {

        if ($request->page == 'capabilities') {
            foreach (self::AVAILABLE_CAPABILITIES as $cap) {
                $role_level = ($request->{$cap . "_min_role"} == 'null') ? 999 : $request->{$cap . "_min_role"};

                if (\Route::current()->getName() == 'template-update') {
                    $capObj = Capability::where('capability_name', $cap . '_' . $request->page_name)->first();
                    $capObj->capability_min_level = $role_level;
                    $capObj->save();
                } else {
                    Capability::create([
                        'capability_name' => $cap . '_' . $request->page_name,
                        'capability_min_level' => $role_level,
                        'site' => SiteController::getSiteID(SiteController::getSite()),
                    ]);
                }
            }

            return true;
        }
    }

    public static function capabilityNameRegex()
    {
        return "/(" . implode('|', self::AVAILABLE_CAPABILITIES) . ")_(.+)/";
    }

    public static function addNewCapability($name, $view, $create, $edit, $delete)
    {
        Capability::create([
            'capability_name' => 'view_' . $name,
            'capability_min_level' => $view,
            'site' => SiteController::getSiteID(SiteController::getSite()),
        ]);

        Capability::create([
            'capability_name' => 'create_' . $name,
            'capability_min_level' => $create,
            'site' => SiteController::getSiteID(SiteController::getSite()),
        ]);

        Capability::create([
            'capability_name' => 'edit_' . $name,
            'capability_min_level' => $edit,
            'site' => SiteController::getSiteID(SiteController::getSite()),
        ]);

        Capability::create([
            'capability_name' => 'delete_' . $name,
            'capability_min_level' => $delete,
            'site' => SiteController::getSiteID(SiteController::getSite()),
        ]);
    }

}
